<?php

namespace App\Http\Controllers;

use App\Models\KotaModel;
use App\Models\User;
use App\Models\KotaHasUserModel;
use App\Mail\RequestKatalogEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class KatalogTAController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Tampilkan daftar katalog TA berdasarkan data kota
     */
    public function index(Request $request)
    {
        $query = KotaModel::with(['users' => function($q) {
            $q->whereIn('role', [2, 3]); // Ambil dosen dan mahasiswa
        }]);
        
        // Filter pencarian jika ada
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('nama_kota', 'LIKE', "%{$search}%")
                  ->orWhere('kelas', 'LIKE', "%{$search}%")
                  ->orWhere('periode', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter berdasarkan periode jika ada
        if ($request->has('periode') && !empty($request->periode)) {
            $query->where('periode', $request->periode);
        }
        
        // Filter berdasarkan kelas jika ada
        if ($request->has('kelas') && !empty($request->kelas)) {
            $query->where('kelas', $request->kelas);
        }
        
        // Hanya tampilkan kota yang memiliki anggota
        $query->whereHas('users');
        
        $katalog = $query->orderBy('periode', 'desc')
                        ->orderBy('nama_kota', 'asc')
                        ->paginate(9);
        
        // Ambil data untuk filter dropdown
        $periodeList = KotaModel::whereHas('users')
                               ->select('periode')
                               ->distinct()
                               ->orderBy('periode', 'desc')
                               ->pluck('periode');
                               
        $kelasList = KotaModel::whereHas('users')
                             ->select('kelas')
                             ->distinct()
                             ->orderBy('kelas', 'asc')
                             ->pluck('kelas');
        
        return view('katalog_ta.index', compact('katalog', 'periodeList', 'kelasList'));
    }
    
    /**
     * Tampilkan detail katalog TA berdasarkan kota
     */
    public function show($id)
    {
        $kota = KotaModel::with(['users'])->findOrFail($id);
        
        // Pisahkan mahasiswa dan dosen
        $mahasiswa = $kota->users->where('role', 3);
        $dosen = $kota->users->where('role', 2);
        
        // Ambil data artefak yang sudah dikumpulkan untuk kota ini
        $artefakKota = DB::table('tbl_kota_has_artefak')
                        ->join('tbl_artefak', 'tbl_kota_has_artefak.id_artefak', '=', 'tbl_artefak.id_artefak')
                        ->where('tbl_kota_has_artefak.id_kota', $id)
                        ->select('tbl_artefak.*', 'tbl_kota_has_artefak.file_pengumpulan', 'tbl_kota_has_artefak.waktu_pengumpulan')
                        ->get();
        
        // Ambil tahapan progres kota
        $tahapanProgres = DB::table('tbl_kota_has_tahapan_progres')
                           ->join('tbl_master_tahapan_progres', 'tbl_kota_has_tahapan_progres.id_master_tahapan_progres', '=', 'tbl_master_tahapan_progres.id')
                           ->where('tbl_kota_has_tahapan_progres.id_kota', $id)
                           ->select('tbl_master_tahapan_progres.nama_progres', 'tbl_kota_has_tahapan_progres.status')
                           ->get();
        
        return view('katalog_ta.show', compact('kota', 'mahasiswa', 'dosen', 'artefakKota', 'tahapanProgres'));
    }
    
    /**
     * Tampilkan form request untuk mengakses detail kota/TA
     */
    public function showRequestForm($id)
    {
        $kota = KotaModel::with(['users'])->findOrFail($id);
        
        // Pisahkan mahasiswa dan dosen
        $mahasiswa = $kota->users->where('role', 3);
        $dosen = $kota->users->where('role', 2);
        
        return view('katalog_ta.request_form', compact('kota', 'mahasiswa', 'dosen'));
    }
    
    /**
     * Proses request untuk mengakses informasi kota/TA menggunakan Laravel Mail
     */
    public function sendRequest(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'tujuan_request' => 'required|string|min:10|max:1000',
            'pesan' => 'nullable|string|max:2000',
        ], [
            'tujuan_request.required' => 'Tujuan request harus diisi.',
            'tujuan_request.min' => 'Tujuan request minimal 10 karakter.',
            'tujuan_request.max' => 'Tujuan request maksimal 1000 karakter.',
            'pesan.max' => 'Pesan tambahan maksimal 2000 karakter.',
        ]);
        
        $kota = KotaModel::with(['users'])->findOrFail($id);
        $requester = Auth::user();
        
        // Ambil email mahasiswa dan dosen dari kota ini
        $emailList = $kota->users->pluck('email')->filter()->unique()->toArray();
        
        if (empty($emailList)) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada anggota KoTA yang dapat dihubungi saat ini. Silakan coba lagi nanti.');
        }
        
        // Siapkan data email menggunakan format yang sama dengan controller lama
        $emailData = [
            'subject' => 'Request Katalog KoTA: ' . $kota->nama_kota,
            'sender_name' => $requester->name,
            'sender_email' => $requester->email,
            'sender_nim' => $requester->nomor_induk ?? 'N/A',
            'tujuan_request' => $validated['tujuan_request'],
            'pesan' => $validated['pesan'] ?? '',
            'kota_nama' => $kota->nama_kota,
            'judul_ta' => $kota->judul,
            'periode' => $kota->periode,
            'kelas' => $kota->kelas,
            'request_date' => now()->format('d F Y H:i'),
        ];
        
        // Log aktivitas request
        Log::info('Request katalog TA', [
            'requester_id' => $requester->id,
            'requester_email' => $requester->email,
            'kota_id' => $kota->id_kota,
            'kota_nama' => $kota->nama_kota,
            'timestamp' => now()
        ]);
        
        // Kirim email ke semua anggota kota menggunakan Laravel Mail
        try {
            $successCount = 0;
            $failedEmails = [];
            
            foreach ($emailList as $email) {
                try {
                    // Gunakan RequestKatalogEmail Mailable class
                    Mail::to($email)->send(new RequestKatalogEmail($emailData));
                    
                    Log::info('Email request katalog TA berhasil dikirim', [
                        'to' => $email,
                        'subject' => $emailData['subject'],
                        'from' => $requester->email,
                        'kota' => $kota->nama_kota
                    ]);
                    $successCount++;
                    
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim email request katalog', [
                        'to' => $email,
                        'error' => $e->getMessage()
                    ]);
                    $failedEmails[] = $email;
                }
            }
            
            // Simpan ke database untuk tracking
            $this->saveRequestToDatabase($requester->id, $kota->id_kota, $validated);
            
            if ($successCount > 0) {
                $message = "Request berhasil dikirim ke {$successCount} anggota KoTA ({$kota->nama_kota}). ";
                $message .= "Mereka akan menghubungi Anda di email {$requester->email} jika bersedia berbagi informasi.";
                
                if (!empty($failedEmails)) {
                    $message .= " Namun, ada " . count($failedEmails) . " email yang gagal dikirim.";
                }
                
                return redirect()->route('katalog-ta.show', $id)->with('success', $message);
            } else {
                return redirect()->back()->withInput()
                    ->with('error', 'Semua email gagal dikirim. Periksa konfigurasi email server atau coba lagi nanti.');
            }
                
        } catch (\Exception $e) {
            Log::error('Error saat mengirim request katalog TA', [
                'requester_id' => $requester->id,
                'kota_id' => $kota->id_kota,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat mengirim request. Silakan coba lagi atau hubungi administrator.');
        }
    }
    
    /**
     * Simpan request ke database untuk tracking
     */
    private function saveRequestToDatabase($requesterId, $kotaId, $data)
    {
        try {
            DB::table('tbl_katalog_requests')->insert([
                'requester_id' => $requesterId,
                'kota_id' => $kotaId,
                'tujuan_request' => $data['tujuan_request'],
                'pesan' => $data['pesan'] ?? null,
                'status' => 'sent',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            // Jika tabel belum ada, buat tabel atau log error
            Log::warning('Gagal menyimpan request ke database', [
                'error' => $e->getMessage(),
                'requester_id' => $requesterId,
                'kota_id' => $kotaId
            ]);
        }
    }
    
    /**
     * Download file artefak tertentu (hanya untuk anggota kota atau admin)
     */
    public function downloadArtefak($kotaId, $artefakId)
    {
        $kota = KotaModel::findOrFail($kotaId);
        $user = Auth::user();
        
        // Cek apakah user adalah anggota kota ini atau admin
        $isAnggota = $kota->users->contains('id', $user->id);
        $isAdmin = $user->role == 1; // Assuming role 1 is admin
        
        if (!$isAnggota && !$isAdmin) {
            return redirect()->route('katalog-ta.show', $kotaId)
                ->with('error', 'Anda tidak memiliki akses untuk mengunduh file ini. Silakan ajukan request terlebih dahulu.');
        }
        
        // Ambil informasi file artefak
        $artefak = DB::table('tbl_kota_has_artefak')
                    ->join('tbl_artefak', 'tbl_kota_has_artefak.id_artefak', '=', 'tbl_artefak.id_artefak')
                    ->where('tbl_kota_has_artefak.id_kota', $kotaId)
                    ->where('tbl_kota_has_artefak.id_artefak', $artefakId)
                    ->select('tbl_kota_has_artefak.file_pengumpulan', 'tbl_artefak.nama_artefak')
                    ->first();
        
        if (!$artefak || !$artefak->file_pengumpulan) {
            return redirect()->route('katalog-ta.show', $kotaId)
                ->with('error', 'File artefak tidak ditemukan.');
        }
        
        // Periksa apakah file ada di storage
        if (!Storage::disk('public')->exists($artefak->file_pengumpulan)) {
            return redirect()->route('katalog-ta.show', $kotaId)
                ->with('error', 'File tidak ditemukan di server. Mungkin sudah dihapus atau dipindahkan.');
        }
        
        // Log download activity
        Log::info('Download artefak katalog TA', [
            'user_id' => $user->id,
            'kota_id' => $kotaId,
            'artefak_id' => $artefakId,
            'file' => $artefak->file_pengumpulan
        ]);
        
        // Download file
        return Storage::disk('public')->download(
            $artefak->file_pengumpulan, 
            $artefak->nama_artefak . '.pdf'
        );
    }
    
    /**
     * Tampilkan statistik katalog TA
     */
    public function statistics()
    {
        $stats = [
            'total_kota' => KotaModel::whereHas('users')->count(),
            'kota_aktif' => KotaModel::whereHas('users')->count(),
            'total_mahasiswa' => DB::table('tbl_kota_has_user')
                                  ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
                                  ->where('users.role', 3)
                                  ->count(),
            'total_dosen' => DB::table('tbl_kota_has_user')
                              ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
                              ->where('users.role', 2)
                              ->count(),
        ];
        
        // Statistik per periode
        $statsPeriode = KotaModel::whereHas('users')
                                ->select('periode', DB::raw('count(*) as total'))
                                ->groupBy('periode')
                                ->orderBy('periode', 'desc')
                                ->get();
        
        // Statistik per kelas
        $statsKelas = KotaModel::whereHas('users')
                              ->select('kelas', DB::raw('count(*) as total'))
                              ->groupBy('kelas')
                              ->orderBy('kelas', 'asc')
                              ->get();
        
        // Statistik artefak yang sudah dikumpulkan
        $statsArtefak = DB::table('tbl_kota_has_artefak')
                         ->join('tbl_kota', 'tbl_kota_has_artefak.id_kota', '=', 'tbl_kota.id_kota')
                         ->join('tbl_kota_has_user', 'tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
                         ->select('tbl_kota.periode', DB::raw('count(DISTINCT tbl_kota_has_artefak.id_kota) as total_artefak'))
                         ->groupBy('tbl_kota.periode')
                         ->orderBy('tbl_kota.periode', 'desc')
                         ->get();
        
        return view('katalog_ta.statistics', compact('stats', 'statsPeriode', 'statsKelas', 'statsArtefak'));
    }
    
    /**
     * API endpoint untuk mendapatkan data kota berdasarkan filter
     */
    public function getKotaData(Request $request)
    {
        $query = KotaModel::with(['users' => function($q) {
            $q->whereIn('role', [2, 3]); // Ambil dosen dan mahasiswa
        }])->whereHas('users'); // Hanya kota yang punya anggota
        
        if ($request->has('periode')) {
            $query->where('periode', $request->periode);
        }
        
        if ($request->has('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        
        $data = $query->get()->map(function($kota) {
            return [
                'id' => $kota->id_kota,
                'nama_kota' => $kota->nama_kota,
                'judul' => $kota->judul,
                'kelas' => $kota->kelas,
                'periode' => $kota->periode,
                'mahasiswa_count' => $kota->users->where('role', 3)->count(),
                'dosen_count' => $kota->users->where('role', 2)->count(),
                'mahasiswa' => $kota->users->where('role', 3)->map(function($user) {
                    return [
                        'name' => $user->name,
                        'nim' => $user->nomor_induk,
                        'email' => $user->email,
                    ];
                }),
                'dosen' => $kota->users->where('role', 2)->map(function($user) {
                    return [
                        'name' => $user->name,
                        'email' => $user->email,
                    ];
                }),
            ];
        });
        
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'total' => $data->count()
        ]);
    }
}