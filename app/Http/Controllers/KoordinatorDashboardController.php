<?php
namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\YudisiumModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KoordinatorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->get('search'); // ⬅️ Tambahkan pencarian

        $seminar1Artefak = [ 'FTA 01', 'FTA 02', 'FTA 03', 'FTA 04', 'FTA 05a', 'Proposal Tugas Akhir' ];
        $seminar2Artefak = [ 'FTA 06', 'FTA 07', 'FTA 08', 'FTA 09', 'FTA 06a', 'FTA 09a', 'SRS', 'SDD', 'Laporan Tugas Akhir' ];
        $seminar3Artefak = [ 'FTA 10', 'FTA 11', 'FTA 12' ];
        $sidangArtefak = [ 'FTA 13', 'FTA 14', 'FTA 15', 'FTA 16', 'FTA 17', 'FTA 18', 'FTA 19' ];

        // Ambil kelas yang dipegang koordinator
        $kelasKoordinator = \DB::table('tbl_koor_has_kelas')
            ->where('id_user', $user->id)
            ->pluck('kelas')
            ->toArray();

        // ========== QUERY UNTUK CHART & STATISTIK (tanpa search) ==========
        $statistikQuery = Kota::with(['tahapanProgress.masterTahapan'])->whereIn('kelas', $kelasKoordinator);

        if ($request->filled('periode')) $statistikQuery->where('periode', $request->periode);
        if ($request->filled('kelas')) $statistikQuery->where('kelas', $request->kelas);

        $totalKota = $statistikQuery->count();
        $allKota = $statistikQuery->get();

        $tahapanNames = ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'];
        $chartData = array_fill_keys($tahapanNames, 0);

        foreach ($allKota as $kota) {
            // Hitung kumulatif - setiap tahapan menghitung semua KoTA yang sudah melewati tahapan tersebut
            $tahapanProgress = $kota->tahapanProgress->sortBy('id_master_tahapan_progres');
            
            // Hitung tahapan tertinggi yang sudah selesai
            $maxTahapanSelesai = 0;
            foreach ($tahapanProgress as $tp) {
                if ($tp->status === 'selesai' && $tp->id_master_tahapan_progres > $maxTahapanSelesai) {
                    $maxTahapanSelesai = $tp->id_master_tahapan_progres;
                }
            }
            
            // Tambahkan ke semua tahapan yang sudah dilewati (kumulatif)
            for ($i = 1; $i <= $maxTahapanSelesai; $i++) {
                if (isset($tahapanNames[$i - 1])) {
                    $chartData[$tahapanNames[$i - 1]]++;
                }
            }
        }

        $selesai = $statistikQuery->whereHas('tahapanProgress', fn($q) => $q->where('status', 'selesai'), '=', 4)->count();
        $dalamProgres = $totalKota - $selesai;
        $totalKotaUji = $statistikQuery->whereHas('tahapanProgress', fn($q) =>
            $q->where('status', 'selesai')->whereHas('masterTahapan', fn($q) => $q->where('nama_progres', 'Sidang'))
        )->count();

        $chartDataJumlah = array_values($chartData);
        $chartDataPersen = [];
        foreach ($chartData as $val) {
            $chartDataPersen[] = $totalKota > 0 ? round(($val / $totalKota) * 100, 1) : 0;
        }

        // ========== QUERY UNTUK TABEL (dengan search) ==========
        $tabelQuery = Kota::with(['tahapanProgress.masterTahapan'])->whereIn('kelas', $kelasKoordinator);

        if ($request->filled('periode')) $tabelQuery->where('periode', $request->periode);
        if ($request->filled('kelas')) $tabelQuery->where('kelas', $request->kelas);
        if ($search) {
            $tabelQuery->where(function ($q) use ($search) {
                $q->where('nama_kota', 'LIKE', '%' . $search . '%')
                ->orWhere('judul', 'LIKE', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 10);
        $kotaList = $tabelQuery->paginate($perPage)->appends($request->query());

        // ========== Data Lainnya ==========
        $periodes = Kota::whereIn('kelas', $kelasKoordinator)
            ->select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode');

        $kelasList = collect($kelasKoordinator);
        $kelasLabels = [
            1 => 'D3 - A', 2 => 'D3 - B',
            3 => 'D4 - A', 4 => 'D4 - B',
        ];

        $timelineData = DB::table('tbl_timeline')
            ->select('nama_kegiatan as name', 'tanggal_mulai as start', 'tanggal_selesai as end')
            ->orderBy('tanggal_mulai')->get()->toArray();

        $yudisiumModel = new YudisiumModel();
        $totalYudisium1 = $yudisiumModel->getDistribusiYudisium($request->periode, $request->kelas, null, $kelasKoordinator)->where('kategori_yudisium', 1)->first()->jumlah ?? 0;
        $totalYudisium2 = $yudisiumModel->getDistribusiYudisium($request->periode, $request->kelas, null, $kelasKoordinator)->where('kategori_yudisium', 2)->first()->jumlah ?? 0;
        $totalYudisium3 = $yudisiumModel->getDistribusiYudisium($request->periode, $request->kelas, null, $kelasKoordinator)->where('kategori_yudisium', 3)->first()->jumlah ?? 0;

        $seminar1 = DB::table('tbl_kota_has_artefak as kha')->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')->whereIn('a.nama_artefak', $seminar1Artefak)->select('a.nama_artefak', 'kha.*')->get();
        $seminar2 = DB::table('tbl_kota_has_artefak as kha')->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')->whereIn('a.nama_artefak', $seminar2Artefak)->select('a.nama_artefak', 'kha.*')->get();
        $seminar3 = DB::table('tbl_kota_has_artefak as kha')->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')->whereIn('a.nama_artefak', $seminar3Artefak)->select('a.nama_artefak', 'kha.*')->get();
        $sidang    = DB::table('tbl_kota_has_artefak as kha')->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')->whereIn('a.nama_artefak', $sidangArtefak)->select('a.nama_artefak', 'kha.*')->get();

        return view('beranda.koordinator.home', [
            'kotaList' => $kotaList, // ⬅️ sudah filtered by search
            'totalKota' => $totalKota,
            'chartData' => $chartData,
            'chartDataJumlah' => $chartDataJumlah,
            'chartDataPersen' => $chartDataPersen,
            'periodes' => $periodes,
            'kelasList' => $kelasList,
            'kelasLabels' => $kelasLabels,
            'totalYudisium1' => $totalYudisium1,
            'totalYudisium2' => $totalYudisium2,
            'totalYudisium3' => $totalYudisium3,
            'selesai' => $selesai,
            'dalamProgres' => $dalamProgres,
            'totalKotaUji' => $totalKotaUji,
            'timelineData' => $timelineData,
            'seminar1' => $seminar1,
            'seminar2' => $seminar2,
            'seminar3' => $seminar3,
            'sidang'    => $sidang
        ]);
    }

    public function getKotaByYudisium(Request $request)
    {
        $user = auth()->user();
        $kategori = $request->kategori;

        // Ambil kelas yang dipegang koordinator
        $kelasKoordinator = \DB::table('tbl_koor_has_kelas')
            ->where('id_user', $user->id)
            ->pluck('kelas')
            ->toArray();

        $kotaList = \DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->where('tbl_yudisium.kategori_yudisium', $kategori)
            ->whereIn('tbl_kota.kelas', $kelasKoordinator)
            ->select('tbl_kota.nama_kota', 'tbl_kota.judul')
            ->get();

        return response()->json($kotaList);
    }
} 