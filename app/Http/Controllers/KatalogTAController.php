<?php
// File: app/Http/Controllers/KatalogTAController.php

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
        
        // Ambil data artefak yang sudah dikumpulkan untuk kota ini dengan error handling
        $artefakKota = collect(); // Default empty collection
        try {
            $artefakKota = DB::table('tbl_kota_has_artefak')
                            ->join('tbl_artefak', 'tbl_kota_has_artefak.id_artefak', '=', 'tbl_artefak.id_artefak')
                            ->where('tbl_kota_has_artefak.id_kota', $id)
                            ->whereNotNull('tbl_kota_has_artefak.file_pengumpulan')
                            ->select('tbl_artefak.*', 'tbl_kota_has_artefak.file_pengumpulan', 'tbl_kota_has_artefak.waktu_pengumpulan')
                            ->orderBy('tbl_kota_has_artefak.waktu_pengumpulan', 'desc')
                            ->get();
        } catch (\Exception $e) {
            Log::warning('Error fetching artefak kota', [
                'kota_id' => $id,
                'error' => $e->getMessage()
            ]);
        }
        
        // Ambil tahapan progres kota dengan error handling untuk kolom urutan
        $tahapanProgres = collect(); // Default empty collection
        $persentaseProgress = 0;
        
        try {
            // Cek apakah kolom urutan ada di tabel
            $columns = DB::select('SHOW COLUMNS FROM tbl_master_tahapan_progres');
            $hasUrutanColumn = collect($columns)->contains(function($column) {
                return $column->Field === 'urutan';
            });
            
            if ($hasUrutanColumn) {
                // Jika kolom urutan ada
                $tahapanProgres = DB::table('tbl_kota_has_tahapan_progres')
                                   ->join('tbl_master_tahapan_progres', 'tbl_kota_has_tahapan_progres.id_master_tahapan_progres', '=', 'tbl_master_tahapan_progres.id')
                                   ->where('tbl_kota_has_tahapan_progres.id_kota', $id)
                                   ->select('tbl_master_tahapan_progres.nama_progres', 'tbl_kota_has_tahapan_progres.status', 'tbl_master_tahapan_progres.urutan')
                                   ->orderBy('tbl_master_tahapan_progres.urutan', 'asc')
                                   ->get();
            } else {
                // Jika kolom urutan tidak ada, gunakan id sebagai pengurutan
                $tahapanProgres = DB::table('tbl_kota_has_tahapan_progres')
                                   ->join('tbl_master_tahapan_progres', 'tbl_kota_has_tahapan_progres.id_master_tahapan_progres', '=', 'tbl_master_tahapan_progres.id')
                                   ->where('tbl_kota_has_tahapan_progres.id_kota', $id)
                                   ->select('tbl_master_tahapan_progres.nama_progres', 'tbl_kota_has_tahapan_progres.status')
                                   ->orderBy('tbl_master_tahapan_progres.id', 'asc')
                                   ->get();
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching tahapan progres', [
                'kota_id' => $id,
                'error' => $e->getMessage()
            ]);
        }
        
        // Hitung persentase progress
        if ($tahapanProgres->count() > 0) {
            $totalTahapan = $tahapanProgres->count();
            $selesaiTahapan = $tahapanProgres->where('status', 'selesai')->count();
            $persentaseProgress = round(($selesaiTahapan / $totalTahapan) * 100);
        }
        
        return view('katalog_ta.show', compact('kota', 'mahasiswa', 'dosen', 'artefakKota', 'tahapanProgres', 'persentaseProgress'));
    }
    
   /**
     * Tampilkan form request untuk mengakses detail kota/TA
     */
    public function showRequestForm($id)
    {
        $kota = KotaModel::with(['users'])->findOrFail($id);
        
        // Pisahkan mahasiswa dan dosen (untuk informasi referensi saja)
        $mahasiswa = $kota->users->where('role', 3);
        $dosen = $kota->users->where('role', 2);
        
        // ===== SOLUSI: Set koordinator info secara langsung =====
        $koordinator = (object)[
            'name' => 'Dr. Koordinator TA', // Ganti dengan nama sebenarnya
            'email' => 'rindindriani@gmail.com', // Ganti dengan email koordinator sebenarnya
            'status' => 'active'
        ];
        
        // Atau dari environment
        // $koordinator = (object)[
        //     'name' => env('KOORDINATOR_TA_NAME', 'Koordinator TA Jurusan'),
        //     'email' => env('KOORDINATOR_TA_EMAIL', 'koordinator@polban.ac.id'),
        //     'status' => 'active'
        // ];
        
        return view('katalog_ta.request_form', compact('kota', 'mahasiswa', 'dosen', 'koordinator'));
    }
    
    /**
     * Proses request untuk mengakses informasi kota/TA
     * UPDATED: Kirim ke koordinator TA jurusan (sistem terpusat)
     */
    public function sendRequest(Request $request, $id)
    {
        // Validasi input tetap sama
        $validated = $request->validate([
            'tujuan_request' => 'required|string',
            'detail_tujuan' => 'required_if:tujuan_request,lainnya|nullable|string|max:500',
            'status_pemohon' => 'required|string|in:mahasiswa_aktif,mahasiswa_alumni,dosen_internal,dosen_eksternal,peneliti',
            'prioritas' => 'required|string|in:rendah,sedang,tinggi,urgent',
            'deadline_request' => 'nullable|date|after:today',
            'institusi' => 'required|string|max:200',
            'pesan' => 'nullable|string|max:1000',
        ], [
            'tujuan_request.required' => 'Tujuan request harus dipilih.',
            'detail_tujuan.required_if' => 'Detail tujuan harus diisi ketika memilih "Lainnya".',
            'detail_tujuan.max' => 'Detail tujuan maksimal 500 karakter.',
            'status_pemohon.required' => 'Status pemohon harus dipilih.',
            'status_pemohon.in' => 'Status pemohon tidak valid.',
            'prioritas.required' => 'Tingkat prioritas harus dipilih.',
            'prioritas.in' => 'Tingkat prioritas tidak valid.',
            'deadline_request.date' => 'Format tanggal deadline tidak valid.',
            'deadline_request.after' => 'Deadline harus tanggal di masa depan.',
            'institusi.required' => 'Institusi/afiliasi harus diisi.',
            'institusi.max' => 'Institusi/afiliasi maksimal 200 karakter.',
            'pesan.max' => 'Pesan tambahan maksimal 1000 karakter.',
        ]);
        
        $kota = KotaModel::with(['users'])->findOrFail($id);
        $requester = Auth::user();
        
        // ===== SOLUSI: Set koordinator email secara langsung atau dari environment =====
        $koordinatorEmail = null;
        $koordinatorName = 'Koordinator TA Jurusan';
        
        // Option 1: Hardcode email koordinator (Paling mudah untuk testing)
        $koordinatorEmail = 'rindindriani@gmail.com'; // Ganti dengan email koordinator yang sebenarnya
        $koordinatorName = 'Koordinator TA'; // Ganti dengan nama yang sebenarnya
        
        // Option 2: Dari environment variable (.env)
        // $koordinatorEmail = env('KOORDINATOR_TA_EMAIL', 'rindi.hafizh@gmail.com');
        // $koordinatorName = env('KOORDINATOR_TA_NAME', 'Koordinator TA Jurusan');
        
        // Option 3: Dari database (jika sudah ada user koordinator)
        /*
        try {
            $koordinator = User::where('role', 4) // Role koordinator
                            ->orWhere('email', 'koordinator@polban.ac.id')
                            ->orWhere('jabatan', 'LIKE', '%koordinator%')
                            ->first();
            
            if ($koordinator) {
                $koordinatorEmail = $koordinator->email;
                $koordinatorName = $koordinator->name;
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching koordinator from database', ['error' => $e->getMessage()]);
        }
        */
        
        // Validasi email koordinator
        if (!$koordinatorEmail) {
            return redirect()->back()->withInput()
                ->with('error', 'Email koordinator TA belum dikonfigurasi. Silakan hubungi administrator sistem.');
        }
        
        // Mapping untuk display
        $tujuanMapping = [
            'referensi_penelitian' => 'Referensi untuk penelitian serupa',
            'studi_metodologi' => 'Mempelajari metodologi yang digunakan',
            'studi_literatur' => 'Menambah referensi literatur',
            'inspirasi_topik' => 'Mencari inspirasi topik TA',
            'analisis_perbandingan' => 'Analisis perbandingan penelitian',
            'validasi_ide' => 'Validasi ide penelitian',
            'pembelajaran_teknik' => 'Mempelajari teknik/tools yang digunakan',
            'konsultasi_akademik' => 'Konsultasi akademik dengan penulis',
            'kolaborasi_penelitian' => 'Mencari peluang kolaborasi penelitian',
            'lainnya' => $validated['detail_tujuan'] ?? 'Lainnya'
        ];
        
        $statusMapping = [
            'mahasiswa_aktif' => 'Mahasiswa Aktif (sedang menyusun TA)',
            'mahasiswa_alumni' => 'Alumni/Mahasiswa yang telah lulus',
            'dosen_internal' => 'Dosen Internal Jurusan',
            'dosen_eksternal' => 'Dosen Eksternal/Institusi Lain',
            'peneliti' => 'Peneliti/Praktisi'
        ];
        
        $prioritasMapping = [
            'rendah' => 'Rendah (tidak mendesak)',
            'sedang' => 'Sedang (1-2 minggu)',
            'tinggi' => 'Tinggi (3-7 hari)',
            'urgent' => 'Urgent (1-2 hari)'
        ];
        
        $tujuanDisplay = $tujuanMapping[$validated['tujuan_request']] ?? $validated['tujuan_request'];
        $statusDisplay = $statusMapping[$validated['status_pemohon']] ?? $validated['status_pemohon'];
        $prioritasDisplay = $prioritasMapping[$validated['prioritas']] ?? $validated['prioritas'];
        
        // Format deadline
        $deadlineFormatted = null;
        if (!empty($validated['deadline_request'])) {
            $deadlineFormatted = \Carbon\Carbon::parse($validated['deadline_request'])->format('d F Y');
        }
        
        // Ambil info anggota KoTA (untuk informasi di email koordinator)
        $mahasiswa = $kota->users->where('role', 3);
        $dosen = $kota->users->where('role', 2);
        
        // Siapkan data email untuk koordinator
        $emailData = [
            'subject' => "[{$prioritasDisplay}] Request Katalog TA - {$kota->nama_kota}",
            'sender_name' => $requester->name,
            'sender_email' => $requester->email,
            'sender_nim' => $requester->nomor_induk ?? 'N/A',
            'tujuan_request' => $tujuanDisplay,
            'status_pemohon' => $statusDisplay,
            'prioritas' => $prioritasDisplay,
            'prioritas_level' => $validated['prioritas'],
            'deadline_request' => $deadlineFormatted,
            'institusi' => $validated['institusi'],
            'pesan' => $validated['pesan'] ?? '',
            'kota_nama' => $kota->nama_kota,
            'judul_ta' => $kota->judul,
            'periode' => $kota->periode,
            'kelas' => $kota->kelas,
            'request_date' => now()->format('d F Y H:i'),
            'request_id' => 'REQ-' . now()->format('YmdHis') . '-' . $id,
            'koordinator_name' => $koordinatorName,
            'koordinator_email' => $koordinatorEmail,
            // Info anggota KoTA untuk referensi koordinator
            'mahasiswa_list' => $mahasiswa->map(function($mhs) {
                return [
                    'name' => $mhs->name,
                    'nim' => $mhs->nomor_induk,
                    'email' => $mhs->email
                ];
            })->toArray(),
            'dosen_list' => $dosen->map(function($dsn) {
                return [
                    'name' => $dsn->name,
                    'email' => $dsn->email
                ];
            })->toArray()
        ];
        
        // Log aktivitas request
        Log::info('Request katalog TA ke koordinator (sistem terpusat)', [
            'requester_id' => $requester->id,
            'requester_email' => $requester->email,
            'kota_id' => $kota->id_kota,
            'kota_nama' => $kota->nama_kota,
            'tujuan' => $validated['tujuan_request'],
            'koordinator_email' => $koordinatorEmail,
            'sistem_terpusat' => true,
            'timestamp' => now()
        ]);
        
        // ===== Kirim email ke koordinator TA menggunakan class yang sudah ada =====
        try {
            // Gunakan RequestKatalogEmail yang sudah ada
            Mail::to($koordinatorEmail)->send(new RequestKatalogEmail($emailData));
            
            Log::info('Email request katalog TA berhasil dikirim ke koordinator (sistem terpusat)', [
                'to' => $koordinatorEmail,
                'subject' => $emailData['subject'],
                'from' => $requester->email,
                'kota' => $kota->nama_kota,
                'request_id' => $emailData['request_id']
            ]);
            
            // Simpan ke database untuk tracking
            $this->saveRequestToDatabase($requester->id, $kota->id_kota, $validated, $emailData['request_id']);
            
            // Pesan sukses
            $successMessage = "Request katalog TA berhasil dikirim ke Koordinator TA Jurusan ({$koordinatorName} - {$koordinatorEmail}). ";
            $successMessage .= "Anda akan mendapat balasan melalui email {$requester->email} ";
            
            switch($validated['prioritas']) {
                case 'urgent':
                    $successMessage .= "dalam 24 jam.";
                    break;
                case 'tinggi':
                    $successMessage .= "dalam 3 hari kerja.";
                    break;
                case 'sedang':
                    $successMessage .= "dalam 1 minggu.";
                    break;
                default:
                    $successMessage .= "sesuai kebijakan koordinator.";
            }
            
            if (!empty($deadlineFormatted)) {
                $successMessage .= " Deadline kebutuhan Anda: {$deadlineFormatted}.";
            }
            
            return redirect()->route('katalog-ta.show', $id)->with('success', $successMessage);
                
        } catch (\Exception $e) {
            Log::error('Error saat mengirim request katalog TA ke koordinator', [
                'requester_id' => $requester->id,
                'kota_id' => $kota->id_kota,
                'koordinator_email' => $koordinatorEmail,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengirim request ke koordinator TA. Silakan coba lagi atau hubungi administrator sistem.');
        }
    }
    
    /**
     * Simpan request ke database untuk tracking
     */
    private function saveRequestToDatabase($requesterId, $kotaId, $data, $requestId)
    {
        try {
            // Cek apakah tabel ada
            $tables = DB::select('SHOW TABLES LIKE "tbl_katalog_requests"');
            
            if (!empty($tables)) {
                DB::table('tbl_katalog_requests')->insert([
                    'request_id' => $requestId,
                    'requester_id' => $requesterId,
                    'kota_id' => $kotaId,
                    'tujuan_request' => $data['tujuan_request'],
                    'detail_tujuan' => $data['detail_tujuan'] ?? null,
                    'status_pemohon' => $data['status_pemohon'],
                    'prioritas' => $data['prioritas'],
                    'deadline_request' => $data['deadline_request'] ?? null,
                    'institusi' => $data['institusi'],
                    'pesan' => $data['pesan'] ?? null,
                    'status' => 'sent_to_koordinator',
                    'sistem_terpusat' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                Log::info('Request tersimpan di database (sistem terpusat)', [
                    'request_id' => $requestId,
                    'requester_id' => $requesterId,
                    'kota_id' => $kotaId
                ]);
            } else {
                Log::info('Tabel tbl_katalog_requests belum ada, request tidak disimpan ke database');
            }
        } catch (\Exception $e) {
            Log::warning('Gagal menyimpan request ke database', [
                'error' => $e->getMessage(),
                'request_id' => $requestId,
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
            $artefak->nama_artefak . '_' . $kota->nama_kota . '.pdf'
        );
    }
    
    /**
     * Tampilkan statistik katalog TA
     */
    public function statistics()
    {
        $stats = [
            'total_kota' => KotaModel::whereHas('users')->count(),
            'kota_selesai' => 0, // Default value
            'total_mahasiswa' => 0,
            'total_dosen' => 0,
        ];
        
        // Hitung statistik dengan error handling
        try {
            $stats['total_mahasiswa'] = DB::table('tbl_kota_has_user')
                                          ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
                                          ->where('users.role', 3)
                                          ->distinct('users.id')
                                          ->count();
        } catch (\Exception $e) {
            Log::warning('Error counting mahasiswa', ['error' => $e->getMessage()]);
        }
        
        try {
            $stats['total_dosen'] = DB::table('tbl_kota_has_user')
                                      ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
                                      ->where('users.role', 2)
                                      ->distinct('users.id')
                                      ->count();
        } catch (\Exception $e) {
            Log::warning('Error counting dosen', ['error' => $e->getMessage()]);
        }
        
        // Hitung kota selesai dengan safe query
        try {
            $stats['kota_selesai'] = DB::table('tbl_kota')
                ->join('tbl_kota_has_user', 'tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
                ->join('tbl_kota_has_tahapan_progres', 'tbl_kota.id_kota', '=', 'tbl_kota_has_tahapan_progres.id_kota')
                ->where('tbl_kota_has_tahapan_progres.status', 'selesai')
                ->distinct('tbl_kota.id_kota')
                ->count();
        } catch (\Exception $e) {
            Log::warning('Error calculating completed kota', ['error' => $e->getMessage()]);
        }
        
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
        $statsArtefak = collect(); // Default empty
        try {
            $statsArtefak = DB::table('tbl_kota_has_artefak')
                             ->join('tbl_kota', 'tbl_kota_has_artefak.id_kota', '=', 'tbl_kota.id_kota')
                             ->whereNotNull('tbl_kota_has_artefak.file_pengumpulan')
                             ->select('tbl_kota.periode', DB::raw('count(*) as total_artefak'))
                             ->groupBy('tbl_kota.periode')
                             ->orderBy('tbl_kota.periode', 'desc')
                             ->get();
        } catch (\Exception $e) {
            Log::warning('Error fetching artefak statistics', ['error' => $e->getMessage()]);
        }
        
        return view('katalog_ta.statistics', compact('stats', 'statsPeriode', 'statsKelas', 'statsArtefak'));
    }
    
    /**
     * API endpoint untuk mendapatkan data kota berdasarkan filter
     */
    public function getKotaData(Request $request)
    {
        try {
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
            
        } catch (\Exception $e) {
            Log::error('Error in getKotaData API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data kota',
                'data' => [],
                'total' => 0
            ], 500);
        }
    }
}