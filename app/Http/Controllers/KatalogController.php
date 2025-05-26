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

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $proposal_id = 7; // ID artefak proposal
        $poster_id = 8; // ID artefak poster

        // Data untuk filter dropdown
        $categories = KotaModel::select('kategori')->distinct()->whereNotNull('kategori')->get();
        $years = KotaModel::selectRaw('YEAR(periode) as year')->distinct()->orderBy('year', 'desc')->get();
        $prodis = KotaModel::select('prodi')->distinct()->get();
        $metodologis = KotaModel::select('metodologi')->distinct()->whereNotNull('metodologi')->where('metodologi', '!=', '')->get();
        $dosens = User::where('role', 2)->get(); // Dosen pembimbing

        // Query utama
        $query = KotaModel::query()
            ->whereExists(function ($subQuery) use ($proposal_id) {
                $subQuery->select(DB::raw(1))
                    ->from('tbl_kota_has_artefak')
                    ->whereColumn('tbl_kota_has_artefak.id_kota', 'tbl_kota.id_kota')
                    ->where('tbl_kota_has_artefak.id_artefak', $proposal_id);
            });

        // Jika mencari
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $searchWords = explode(' ', trim($searchTerm));

            $validWords = array_filter($searchWords, function ($word) {
                return strlen(trim($word)) >= 3;
            });

            if (count($validWords) >= 3 || (count($validWords) === 1 && strlen($validWords[0]) >= 5)) {
                $query->where(function ($q) use ($validWords, $searchTerm) {
                    $q->where('judul', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('abstrak', 'LIKE', "%{$searchTerm}%");

                    foreach ($validWords as $word) {
                        $q->orWhere('judul', 'LIKE', "%{$word}%")
                            ->orWhere('kategori', 'LIKE', "%{$word}%")
                            ->orWhere('metodologi', 'LIKE', "%{$word}%")
                            ->orWhere('abstrak', 'LIKE', "%{$word}%");
                    }
                });
            }
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter prodi
        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        // Filter metodologi
        if ($request->filled('metodologi')) {
            $query->where('metodologi', $request->metodologi);
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->whereYear('periode', $request->tahun);
        }

        // Filter dosen pembimbing
        if ($request->filled('dosen')) {
            $query->whereExists(function ($subQuery) use ($request) {
                $subQuery->select(DB::raw(1))
                    ->from('tbl_kota_has_user')
                    ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
                    ->whereColumn('tbl_kota_has_user.id_kota', 'tbl_kota.id_kota')
                    ->where('users.id', $request->dosen)
                    ->where('users.role', 2);
            });
        }

        // Dapatkan hasil dengan pagination
        $katalog = $query->paginate(12);

        // Tambahkan informasi poster untuk setiap item
        foreach ($katalog as $item) {
            $poster = DB::table('tbl_kota_has_artefak')
                ->where('id_kota', $item->id_kota)
                ->where('id_artefak', $poster_id)
                ->select('file_pengumpulan')
                ->first();

            $item->poster_file = $poster ? $poster->file_pengumpulan : null;
        }

        return view('katalog.katalog', compact('prodis', 'katalog', 'categories', 'years', 'metodologis', 'dosens'));
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
        
        return view('katalog.request_form', compact('kota', 'mahasiswa', 'dosen', 'koordinator'));
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
            
            return redirect()->route('laporan.show', $id)->with('success', $successMessage);
                
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
}