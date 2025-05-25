<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\YudisiumModel;
use App\Models\YudisiumLogModel;
use App\Models\KotaModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\YudisiumExport;

class YudisiumController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of yudisium.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Cek apakah user memiliki hak akses (koordinator TA atau kaprodi)
        $user = Auth::user();
        if (!in_array($user->role, [1, 4, 5])) { // 1=Koordinator, 4=Kaprodi D3, 5=Kaprodi D4
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Filter parameter
        $periode = $request->get('periode', Carbon::now()->year);
        $kelas = $request->get('kelas', null);
        $kategori = $request->get('kategori', null);
        $status = $request->get('status', null);

        // Query data yudisium
        $query = DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->leftJoin('tbl_kota_has_user', 'tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
            ->leftJoin('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
            ->select(
                'tbl_yudisium.id_yudisium',
                'tbl_yudisium.id_kota as yudisium_id_kota',
                'tbl_yudisium.kategori_yudisium',
                'tbl_yudisium.tanggal_yudisium',
                'tbl_yudisium.nilai_akhir',
                'tbl_yudisium.status',
                'tbl_yudisium.keterangan',
                'tbl_yudisium.created_at',
                'tbl_yudisium.updated_at',
                'tbl_kota.nama_kota',
                'tbl_kota.judul',
                'tbl_kota.kelas',
                'tbl_kota.periode',
                DB::raw('GROUP_CONCAT(DISTINCT CASE WHEN users.role = 3 THEN users.name ELSE NULL END) as mahasiswa'),
                DB::raw('GROUP_CONCAT(DISTINCT CASE WHEN users.role = 2 THEN users.name ELSE NULL END) as dosen')
            )
            ->groupBy(
                'tbl_yudisium.id_yudisium',
                'tbl_yudisium.id_kota',
                'tbl_yudisium.kategori_yudisium',
                'tbl_yudisium.tanggal_yudisium',
                'tbl_yudisium.nilai_akhir',
                'tbl_yudisium.status',
                'tbl_yudisium.keterangan',
                'tbl_yudisium.created_at',
                'tbl_yudisium.updated_at',
                'tbl_kota.nama_kota',
                'tbl_kota.judul',
                'tbl_kota.kelas',
                'tbl_kota.periode'
            );

        // Terapkan filter
        if ($periode) {
            $query->where('tbl_kota.periode', $periode);
        }
        if ($kelas) {
            $query->where('tbl_kota.kelas', $kelas);
        }
        if ($kategori) {
            $query->where('tbl_yudisium.kategori_yudisium', $kategori);
        }
        if ($status) {
            $query->where('tbl_yudisium.status', $status);
        }

        // Jalankan query
        $yudisium = $query->paginate(10);

        // Data untuk filter dropdown
        $periodeList = DB::table('tbl_kota')->distinct()->pluck('periode');
        $kelasList = DB::table('tbl_kota')->distinct()->pluck('kelas');

        // Data untuk chart distribusi
        $distribusi = DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->select('tbl_yudisium.kategori_yudisium', DB::raw('COUNT(*) as jumlah'))
            ->where('tbl_kota.periode', $periode)
            ->when($kelas, function ($query, $kelas) {
                return $query->where('tbl_kota.kelas', $kelas);
            })
            ->groupBy('tbl_yudisium.kategori_yudisium')
            ->get();

        // Mengembalikan view dengan data
        // return view('yudisium.index', compact('yudisium', 'periodeList', 'kelasList', 'distribusi', 'periode', 'kelas', 'kategori', 'status'));
        return view('yudisium.kelola', compact('yudisium', 'periodeList', 'kelasList', 'periode', 'kelas', 'kategori', 'status'));
    }

    /**
     * Show the form for creating a new yudisium.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        // Cek akses
        $user = Auth::user();
        if (!in_array($user->role, [1, 4, 5])) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Daftar KoTA yang belum memiliki data yudisium
        $kota = DB::table('tbl_kota')
            ->leftJoin('tbl_yudisium', 'tbl_kota.id_kota', '=', 'tbl_yudisium.id_kota')
            ->whereNull('tbl_yudisium.id_yudisium')
            ->select('tbl_kota.*')
            ->get();

        return view('yudisium.create', compact('kota'));
    }

    /**
     * Store a newly created yudisium in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_kota' => 'required|exists:tbl_kota,id_kota',
            'kategori_yudisium' => 'required|in:1,2,3',
            'tanggal_yudisium' => 'required|date',
            'nilai_akhir' => 'nullable|numeric|between:0,4',
            'status' => 'required|in:pending,approved,rejected',
            'keterangan' => 'nullable|string',
        ]);

        // Cek apakah KoTA sudah memiliki data yudisium
        $existingYudisium = YudisiumModel::where('id_kota', $request->id_kota)->first();
        if ($existingYudisium) {
            return redirect()->back()->with('error', 'KoTA ini sudah memiliki data yudisium')->withInput();
        }

        // Buat data yudisium baru
        $yudisium = YudisiumModel::create($request->all());

        // Update status_yudisium di tabel kota
        DB::table('tbl_kota')
            ->where('id_kota', $request->id_kota)
            ->update(['status_yudisium' => $request->status]);

        // Buat log
        YudisiumLogModel::create([
            'id_yudisium' => $yudisium->id_yudisium,
            'id_user' => Auth::id(),
            'jenis_perubahan' => 'Penambahan Data',
            'nilai_lama' => null,
            'nilai_baru' => json_encode($request->all()),
            'keterangan' => 'Penambahan data yudisium baru',
        ]);

        return redirect()->route('yudisium.kelola')->with('success', 'Data yudisium berhasil ditambahkan');
    }

    /**
     * Display the specified yudisium.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        // Cek akses
        $user = Auth::user();
        if (!in_array($user->role, [1, 2, 3, 4, 5])) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Ambil data yudisium
        $yudisium = DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->leftJoin('tbl_kota_has_user', 'tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
            ->leftJoin('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
            ->select(
                'tbl_yudisium.id_yudisium',
                'tbl_yudisium.id_kota as yudisium_id_kota',
                'tbl_yudisium.kategori_yudisium',
                'tbl_yudisium.tanggal_yudisium',
                'tbl_yudisium.nilai_akhir',
                'tbl_yudisium.status',
                'tbl_yudisium.keterangan',
                'tbl_yudisium.created_at',
                'tbl_yudisium.updated_at',
                'tbl_kota.nama_kota',
                'tbl_kota.judul',
                'tbl_kota.kelas',
                'tbl_kota.periode',
                DB::raw('GROUP_CONCAT(DISTINCT CASE WHEN users.role = 3 THEN users.name ELSE NULL END) as mahasiswa'),
                DB::raw('GROUP_CONCAT(DISTINCT CASE WHEN users.role = 2 THEN users.name ELSE NULL END) as dosen')
            )
            ->where('tbl_yudisium.id_yudisium', $id)
            ->groupBy(
                'tbl_yudisium.id_yudisium',
                'tbl_yudisium.id_kota',
                'tbl_yudisium.kategori_yudisium',
                'tbl_yudisium.tanggal_yudisium',
                'tbl_yudisium.nilai_akhir',
                'tbl_yudisium.status',
                'tbl_yudisium.keterangan',
                'tbl_yudisium.created_at',
                'tbl_yudisium.updated_at',
                'tbl_kota.nama_kota',
                'tbl_kota.judul',
                'tbl_kota.kelas',
                'tbl_kota.periode'
            )
            ->first();

        if (!$yudisium) {
            return redirect()->route('yudisium.kelola')->with('error', 'Data yudisium tidak ditemukan');
        }

        // Ambil log perubahan
        $logs = DB::table('tbl_yudisium_log')
            ->join('users', 'tbl_yudisium_log.id_user', '=', 'users.id')
            ->where('tbl_yudisium_log.id_yudisium', $id)
            ->select('tbl_yudisium_log.*', 'users.name as nama_user')
            ->orderBy('tbl_yudisium_log.waktu_perubahan', 'desc')
            ->get();

        // Periksa apakah user adalah mahasiswa yang terkait dengan KoTA ini
        $isRelatedMahasiswa = false;
        if ($user->role == 3) {
            $isRelatedMahasiswa = DB::table('tbl_kota_has_user')
                ->where('id_kota', $yudisium->id_kota)
                ->where('id_user', $user->id)
                ->exists();

            if (!$isRelatedMahasiswa) {
                return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke data yudisium ini');
            }
        }

        // Periksa apakah user adalah dosen pembimbing yang terkait dengan KoTA ini
        $isRelatedDosen = false;
        if ($user->role == 2) {
            $isRelatedDosen = DB::table('tbl_kota_has_user')
                ->where('id_kota', $yudisium->id_kota)
                ->where('id_user', $user->id)
                ->exists();

            if (!$isRelatedDosen) {
                return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke data yudisium ini');
            }
        }

        // return view('yudisium.show', compact('yudisium', 'logs'));
        return view('yudisium.detail', compact('yudisium', 'logs'));
    }

    /**
     * Show the form for editing the specified yudisium.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        // Cek akses
        $user = Auth::user();
        if (!in_array($user->role, [1, 4, 5])) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Ambil data yudisium
        $yudisium = YudisiumModel::findOrFail($id);
        $kota = KotaModel::findOrFail($yudisium->id_kota);

        return view('yudisium.edit', compact('yudisium', 'kota'));
    }

    /**
     * Update the specified yudisium in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'kategori_yudisium' => 'required|in:1,2,3',
            'tanggal_yudisium' => 'required|date',
            'nilai_akhir' => 'nullable|numeric|between:0,4',
            'status' => 'required|in:pending,approved,rejected',
            'keterangan' => 'nullable|string',
        ]);

        // Ambil data yudisium lama
        $yudisium = YudisiumModel::findOrFail($id);
        $oldData = $yudisium->toArray();

        // Update data yudisium
        $yudisium->update($request->all());

        // Update status_yudisium di tabel kota
        DB::table('tbl_kota')
            ->where('id_kota', $yudisium->id_kota)
            ->update(['status_yudisium' => $request->status]);

        // Buat log perubahan
        YudisiumLogModel::create([
            'id_yudisium' => $id,
            'id_user' => Auth::id(),
            'jenis_perubahan' => 'Pembaruan Data',
            'nilai_lama' => json_encode($oldData),
            'nilai_baru' => json_encode($yudisium->toArray()),
            'keterangan' => 'Pembaruan data yudisium',
        ]);

        return redirect()->route('yudisium.index')->with('success', 'Data yudisium berhasil diperbarui');
    }

    /**
     * Remove the specified yudisium from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Cek akses
        $user = Auth::user();
        if (!in_array($user->role, [1, 4, 5])) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk menghapus data ini');
        }

        // Ambil data yudisium
        $yudisium = YudisiumModel::findOrFail($id);
        $idKota = $yudisium->id_kota;

        // Hapus data yudisium
        $yudisium->delete();

        // Update status_yudisium di tabel kota
        DB::table('tbl_kota')
            ->where('id_kota', $idKota)
            ->update(['status_yudisium' => null]);

        return redirect()->route('yudisium.kelola')->with('success', 'Data yudisium berhasil dihapus');
    }

    /**
     * Export data yudisium to Excel.
     *
     * @param  Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        // Cek akses
        $user = Auth::user();
        if (!in_array($user->role, [1, 4, 5])) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk mengekspor data');
        }

        // Filter parameter
        $periode = $request->get('periode', Carbon::now()->year);
        $kelas = $request->get('kelas', null);
        $kategori = $request->get('kategori', null);
        $status = $request->get('status', null);

        // Nama file Excel
        $fileName = 'yudisium_' . $periode;
        if ($kelas) {
            $fileName .= '_kelas_' . $kelas;
        }
        $fileName .= '_' . date('Y-m-d') . '.xlsx';

        // Export ke Excel
        return Excel::download(new YudisiumExport($periode, $kelas, $kategori, $status), $fileName);
    }

    /**
     * Display dashboard for yudisium data.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard(Request $request)
    {
        // Cek akses
        $user = Auth::user();
        if (!in_array($user->role, [1, 4, 5])) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Filter parameter
        $periode = $request->get('periode', Carbon::now()->year);
        $kelas = $request->get('kelas', null);

        // Data untuk filter dropdown
        $periodeList = DB::table('tbl_kota')->distinct()->pluck('periode');
        $kelasList = DB::table('tbl_kota')->distinct()->pluck('kelas');

        // Data untuk grafik distribusi kategori yudisium
        $distribusiKategori = DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->select('tbl_yudisium.kategori_yudisium', DB::raw('COUNT(*) as jumlah'))
            ->where('tbl_kota.periode', $periode)
            ->when($kelas, function ($query, $kelas) {
                return $query->where('tbl_kota.kelas', $kelas);
            })
            ->groupBy('tbl_yudisium.kategori_yudisium')
            ->get();

        // Data untuk grafik distribusi status yudisium
        $distribusiStatus = DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->select('tbl_yudisium.status', DB::raw('COUNT(*) as jumlah'))
            ->where('tbl_kota.periode', $periode)
            ->when($kelas, function ($query, $kelas) {
                return $query->where('tbl_kota.kelas', $kelas);
            })
            ->groupBy('tbl_yudisium.status')
            ->get();

        // Data untuk grafik distribusi kelas
        $distribusiKelas = DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->select('tbl_kota.kelas', DB::raw('COUNT(*) as jumlah'))
            ->where('tbl_kota.periode', $periode)
            ->groupBy('tbl_kota.kelas')
            ->get();

        // Statistik umum
        $totalKota = DB::table('tbl_kota')
            ->where('periode', $periode)
            ->when($kelas, function ($query, $kelas) {
                return $query->where('kelas', $kelas);
            })
            ->count();

        $totalYudisium = DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->where('tbl_kota.periode', $periode)
            ->when($kelas, function ($query, $kelas) {
                return $query->where('tbl_kota.kelas', $kelas);
            })
            ->count();

        $belumYudisium = $totalKota - $totalYudisium;

        $rataRataNilai = DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->where('tbl_kota.periode', $periode)
            ->when($kelas, function ($query, $kelas) {
                return $query->where('tbl_kota.kelas', $kelas);
            })
            ->avg('tbl_yudisium.nilai_akhir');

        return view('yudisium.dashboard', compact(
            'distribusiKategori',
            'distribusiStatus',
            'distribusiKelas',
            'totalKota',
            'totalYudisium',
            'belumYudisium',
            'rataRataNilai',
            'periodeList',
            'kelasList',
            'periode',
            'kelas'
        ));
    }

    /**
     * Display view for student/dosen to check yudisium status.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function status()
    {
        $user = Auth::user();

        // Cek apakah user adalah mahasiswa
        if ($user->role == 3) {
            // Ambil data KoTA yang terkait dengan mahasiswa
            $kota = DB::table('tbl_kota_has_user')
                ->join('tbl_kota', 'tbl_kota_has_user.id_kota', '=', 'tbl_kota.id_kota')
                ->where('tbl_kota_has_user.id_user', $user->id)
                ->select('tbl_kota.*')
                ->first();

            if (!$kota) {
                return view('yudisium.status_mahasiswa', ['yudisium' => null]);
            }

            // Ambil data yudisium terkait
            $yudisium = DB::table('tbl_yudisium')
                ->where('id_kota', $kota->id_kota)
                ->first();

            return view('yudisium.status_mahasiswa', compact('yudisium', 'kota'));
        }
        // Cek apakah user adalah dosen
        elseif ($user->role == 2) {
            // Ambil data KoTA yang dibimbing oleh dosen
            $kotas = DB::table('tbl_kota_has_user')
                ->join('tbl_kota', 'tbl_kota_has_user.id_kota', '=', 'tbl_kota.id_kota')
                ->where('tbl_kota_has_user.id_user', $user->id)
                ->select('tbl_kota.*')
                ->get();

            $kotaIds = $kotas->pluck('id_kota')->toArray();

            // Ambil data mahasiswa dan yudisium
            $mahasiswaYudisium = DB::table('tbl_kota_has_user')
                ->join('tbl_kota', 'tbl_kota_has_user.id_kota', '=', 'tbl_kota.id_kota')
                ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
                ->leftJoin('tbl_yudisium', 'tbl_kota.id_kota', '=', 'tbl_yudisium.id_kota')
                ->whereIn('tbl_kota.id_kota', $kotaIds)
                ->where('users.role', 3)
                ->select(
                    'users.name as nama_mahasiswa',
                    'users.nomor_induk as nim',
                    'tbl_kota.nama_kota',
                    'tbl_kota.judul',
                    'tbl_kota.kelas',
                    'tbl_kota.periode',
                    'tbl_yudisium.kategori_yudisium',
                    'tbl_yudisium.tanggal_yudisium',
                    'tbl_yudisium.nilai_akhir',
                    'tbl_yudisium.status',
                    'tbl_yudisium.id_yudisium'
                )
                ->get();

            return view('yudisium.status_dosen', compact('mahasiswaYudisium'));
        } else {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
    }

    /**
 * Generate yudisium categories automatically.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function generate(Request $request) {
    // Cek akses
    $user = Auth::user();
    if (!in_array($user->role, [1, 4, 5])) {
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk melakukan generate yudisium');
    }

    // Validasi input
    $request->validate([
        'periode_generate' => 'required'
    ]);

    $periode = $request->periode_generate;

    // Ambil semua KoTA yang belum memiliki yudisium pada periode ini
    $kotaList = DB::table('tbl_kota')
        ->leftJoin('tbl_yudisium', 'tbl_kota.id_kota', '=', 'tbl_yudisium.id_kota')
        ->where('tbl_kota.periode', $periode)
        ->whereNull('tbl_yudisium.id_yudisium')
        ->select('tbl_kota.*')
        ->get();

    // Kriteria yudisium (contoh, sesuaikan dengan kebijakan resmi)
    $batasYudisium = Carbon::parse('2025-05-30'); // Contoh tenggat yudisium
    $tanggalSekarang = Carbon::now();

    foreach ($kotaList as $kota) {
        // Simulasi nilai akhir (ganti dengan data sebenarnya jika ada)
        $nilaiAkhir = rand(60, 100); // Contoh nilai acak, ganti dengan data dari tabel lain jika ada
        $tanggalYudisium = $tanggalSekarang;

        // Tentukan kategori
        if ($nilaiAkhir >= 85 && $tanggalYudisium->lte($batasYudisium)) {
            $kategoriYudisium = 1; // Yudisium 1
        } elseif ($nilaiAkhir >= 75 && $tanggalYudisium->lte($batasYudisium->copy()->addDays(14))) {
            $kategoriYudisium = 2; // Yudisium 2
        } else {
            $kategoriYudisium = 3; // Yudisium 3
        }

        // Simpan data yudisium
        $yudisium = YudisiumModel::create([
            'id_kota' => $kota->id_kota,
            'kategori_yudisium' => $kategoriYudisium,
            'tanggal_yudisium' => $tanggalYudisium,
            'nilai_akhir' => $nilaiAkhir,
            'status' => 'pending',
            'keterangan' => 'Generated automatically',
        ]);

        // Update status_yudisium di tabel kota
        DB::table('tbl_kota')
            ->where('id_kota', $kota->id_kota)
            ->update(['status_yudisium' => 'pending']);

        // Buat log
        YudisiumLogModel::create([
            'id_yudisium' => $yudisium->id_yudisium,
            'id_user' => Auth::id(),
            'jenis_perubahan' => 'Generate Otomatis',
            'nilai_lama' => null,
            'nilai_baru' => json_encode([
                'kategori_yudisium' => $kategoriYudisium,
                'nilai_akhir' => $nilaiAkhir,
                'tanggal_yudisium' => $tanggalYudisium->toDateString(),
            ]),
            'keterangan' => 'Generate kategori yudisium otomatis',
        ]);
    }

    return redirect()->route('yudisium.kelola')->with('success', 'Kategori yudisium berhasil di-generate.');
    }
}