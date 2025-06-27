<?php
namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\YudisiumModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaprodiDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->get('search');
        
        $seminar1Artefak = [
            'FTA 01', 'FTA 02', 'FTA 03', 'FTA 04', 'FTA 05a', 'Proposal Tugas Akhir'
        ];
        $seminar2Artefak = [
            'FTA 06', 'FTA 07', 'FTA 08', 'FTA 09', 'FTA 06a', 'FTA 09a',
            'SRS', 'SDD', 'Laporan Tugas Akhir'
        ];
        $seminar3Artefak = [
            'FTA 10', 'FTA 11', 'FTA 12'
        ];
        $sidangArtefak = [
            'FTA 13', 'FTA 14', 'FTA 15', 'FTA 16', 'FTA 17', 'FTA 18', 'FTA 19'
        ];

        // Tentukan kelas yang dipegang kaprodi
        if ($user->role == 5) {
            $kelasKaprodi = [1, 2]; // D3
            $kotaIds = Kota::whereIn('kelas', $kelasKaprodi)
                ->pluck('id_kota')
                ->toArray();
            $getYudisium = (new YudisiumModel())->getDistribusiYudisiumD3($request->periode, $request->kelas, $kotaIds, $kelasKaprodi);
        } else {
            $kelasKaprodi = [3, 4]; // D4
            $kotaIds = Kota::whereIn('kelas', $kelasKaprodi)
                ->pluck('id_kota')
                ->toArray();
            $getYudisium = (new YudisiumModel())->getDistribusiYudisiumD4($request->periode, $request->kelas, $kotaIds, $kelasKaprodi);
        }

        // ========================================
        // QUERY UNTUK STATISTIK DAN GRAFIK (TANPA SEARCH)
        // ========================================
        $statistikQuery = Kota::with(['tahapanProgress.masterTahapan'])->whereIn('id_kota', $kotaIds);
        
        // Apply filter periode dan kelas untuk statistik
        if ($request->filled('periode')) {
            $statistikQuery->where('periode', $request->periode);
        }
        if ($request->filled('kelas')) {
            $statistikQuery->where('kelas', $request->kelas);
        }

        // Hitung statistik untuk cards (TANPA SEARCH)
        $totalKota = $statistikQuery->count();
        $allKotaForStats = $statistikQuery->get();

        // Generate chart data (TANPA SEARCH)
        $tahapanNames = ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'];
        $chartData = array_fill_keys($tahapanNames, 0);
        foreach ($allKotaForStats as $kota) {
            $lastSelesaiTahapan = $kota->tahapanProgress
                ->where('status', 'selesai')
                ->sortByDesc('id_master_tahapan_progres')
                ->first();
            if ($lastSelesaiTahapan) {
                $namaTahapan = optional($lastSelesaiTahapan->masterTahapan)->nama_progres;
                if (isset($chartData[$namaTahapan])) {
                    $chartData[$namaTahapan]++;
                }
            }
        }

        // Hitung persentase untuk chart (TANPA SEARCH)
        $chartDataJumlah = array_values($chartData);
        $chartDataPersen = [];
        foreach ($chartData as $val) {
            $chartDataPersen[] = $totalKota > 0 ? round(($val / $totalKota) * 100, 1) : 0;
        }
        // Timeline data untuk chart tooltip
        $timelineData = DB::table('tbl_timeline')
            ->select('nama_kegiatan as name', 'tanggal_mulai as start', 'tanggal_selesai as end')
            ->orderBy('tanggal_mulai')
            ->get()
            ->toArray();
            
        $perPage = $request->get('per_page', 10);
        $kotaList = $query->paginate($perPage);
        $totalYudisium1 = $getYudisium->where('kategori_yudisium', 1)->first()->jumlah ?? 0;
        $totalYudisium2 = $getYudisium->where('kategori_yudisium', 2)->first()->jumlah ?? 0;
        $totalYudisium3 = $getYudisium->where('kategori_yudisium', 3)->first()->jumlah ?? 0;
        $selesai = $query->whereHas('tahapanProgress', function ($q) {
            $q->where('status', 'selesai');
        }, '=', 4)->count();
        
        $dalamProgres = $totalKota - $selesai;
        
        $totalKotaUji = $statistikQuery->whereHas('tahapanProgress', function ($q) {
            $q->where('status', 'selesai')
              ->whereHas('masterTahapan', function ($q) {
                  $q->where('nama_progres', 'Sidang');
              });
        })->count();

        // ========================================
        // QUERY UNTUK TABEL LIST KOTA (DENGAN SEARCH)
        // ========================================
        $tabelQuery = Kota::with(['tahapanProgress.masterTahapan'])->whereIn('id_kota', $kotaIds);
        
        // Apply filter periode dan kelas untuk tabel
        if ($request->filled('periode')) {
            $tabelQuery->where('periode', $request->periode);
        }
        if ($request->filled('kelas')) {
            $tabelQuery->where('kelas', $request->kelas);
        }

        // Apply SEARCH hanya untuk tabel
        if ($search) {
            $tabelQuery->where(function ($q) use ($search) {
                $q->where('nama_kota', 'LIKE', '%' . $search . '%')
                  ->orWhere('judul', 'LIKE', '%' . $search . '%');
            });
        }

        // ========================================
        // DATA LAINNYA (TIDAK TERPENGARUH SEARCH)
        // ========================================
        
        // Timeline data untuk chart tooltip
        $timelineData = DB::table('tbl_timeline')
            ->select('nama_kegiatan as name', 'tanggal_mulai as start', 'tanggal_selesai as end')
            ->orderBy('tanggal_mulai')
            ->get()
            ->toArray();

        // Data filter options
        $periodes = Kota::whereIn('id_kota', $kotaIds)
            ->select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode');
        $kelasList = Kota::whereIn('id_kota', $kotaIds)
            ->select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        // Data yudisium (TANPA SEARCH)
        $totalYudisium1 = $getYudisium->where('kategori_yudisium', 1)->first()->jumlah ?? 0;
        $totalYudisium2 = $getYudisium->where('kategori_yudisium', 2)->first()->jumlah ?? 0;
        $totalYudisium3 = $getYudisium->where('kategori_yudisium', 3)->first()->jumlah ?? 0;

        // Data artefak (TANPA SEARCH)
        $seminar1 = DB::table('tbl_kota_has_artefak as kha')
            ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
            ->whereIn('a.nama_artefak', $seminar1Artefak)
            ->whereIn('kha.id_kota', $kotaIds)
            ->select('a.nama_artefak', 'kha.*')
            ->get();

        $seminar2 = DB::table('tbl_kota_has_artefak as kha')
            ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
            ->whereIn('a.nama_artefak', $seminar2Artefak)
            ->whereIn('kha.id_kota', $kotaIds)
            ->select('a.nama_artefak', 'kha.*')
            ->get();

        $seminar3 = DB::table('tbl_kota_has_artefak as kha')
            ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
            ->whereIn('a.nama_artefak', $seminar3Artefak)
            ->whereIn('kha.id_kota', $kotaIds)
            ->select('a.nama_artefak', 'kha.*')
            ->get();

        $sidang = DB::table('tbl_kota_has_artefak as kha')
            ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
            ->whereIn('a.nama_artefak', $sidangArtefak)
            ->whereIn('kha.id_kota', $kotaIds)
            ->select('a.nama_artefak', 'kha.*')
            ->get();

        // ========================================
        // PAGINATION UNTUK TABEL (DENGAN SEARCH)
        // ========================================
        $perPage = $request->get('per_page', 10);
        $kotaList = $tabelQuery->paginate($perPage)->appends($request->query());

        return view('beranda.koordinator.home', [
            'kotaList' => $kotaList, // HANYA INI YANG TERPENGARUH SEARCH
            
            // DATA STATISTIK DAN GRAFIK (TIDAK TERPENGARUH SEARCH)
            'totalKota' => $totalKota,
            'chartData' => $chartData,
            'chartDataJumlah' => $chartDataJumlah,
            'chartDataPersen' => $chartDataPersen,
            'selesai' => $selesai,
            'dalamProgres' => $dalamProgres,
            'totalKotaUji' => $totalKotaUji,
            'totalYudisium1' => $totalYudisium1,
            'totalYudisium2' => $totalYudisium2,
            'totalYudisium3' => $totalYudisium3,
            
            // DATA LAINNYA
            'periodes' => $periodes,
            'kelasList' => $kelasList,
            'seminar1' => $seminar1,
            'seminar2' => $seminar2,
            'seminar3' => $seminar3,
            'sidang' => $sidang,
            'timelineData' => $timelineData
        ]);
    }

    public function getKotaByYudisium(Request $request)
    {
        $user = auth()->user();
        $kategori = $request->kategori;
        $periode = $request->periode;
        $kelas = $request->kelas;

        // Tentukan kelas yang dipegang kaprodi
        if ($user->role == 5) {
            $kelasKaprodi = [1, 2]; // D3
        } else {
            $kelasKaprodi = [3, 4]; // D4
        }

        $kotaList = DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->where('tbl_yudisium.kategori_yudisium', $kategori)
            ->whereIn('tbl_kota.kelas', $kelasKaprodi);

        if ($periode) {
            $kotaList->where('tbl_kota.periode', $periode);
        }
        if ($kelas) {
            $kotaList->where('tbl_kota.kelas', $kelas);
        }

        $kotaList = $kotaList->select('tbl_kota.nama_kota', 'tbl_kota.judul')->get();

        return response()->json($kotaList);
    }
}