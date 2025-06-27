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
        $query = Kota::with(['tahapanProgress'])->whereIn('id_kota', $kotaIds);
        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        $totalKota = $query->count();
        $periodes = Kota::whereIn('id_kota', $kotaIds)
            ->select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode');
        $kelasList = Kota::whereIn('id_kota', $kotaIds)
            ->select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $allKota = $query->with(['tahapanProgress.masterTahapan'])->get();
        $tahapanNames = ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'];
        $chartData = array_fill_keys($tahapanNames, 0);
        foreach ($allKota as $kota) {
            $maxTahapan = 0;
            foreach ($kota->tahapanProgress as $tp) {
                if ($tp->status === 'selesai' && $tp->id_master_tahapan_progres > $maxTahapan) {
                    $maxTahapan = $tp->id_master_tahapan_progres;
                }
            }
            for ($i = 1; $i <= $maxTahapan; $i++) {
                if (isset($tahapanNames[$i - 1])) {
                    $chartData[$tahapanNames[$i - 1]]++;
                }
            }
        }
        $chartDataJumlah = array_values($chartData);
        $chartDataPersen = [];
        foreach ($chartData as $key => $val) {
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
        $totalKotaUji = $query->whereHas('tahapanProgress', function ($q) {
            $q->where('status', 'selesai')
            ->whereHas('masterTahapan', function ($q) {
                $q->where('nama_progres', 'Sidang');
            });
        })->count();
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
        return view('beranda.koordinator.home', [
            'kotaList' => $kotaList,
            'totalKota' => $totalKota,
            'chartData' => $chartData,
            'chartDataJumlah' => $chartDataJumlah,
            'chartDataPersen' => $chartDataPersen,
            'periodes' => $periodes,
            'kelasList' => $kelasList,
            'totalYudisium1' => $totalYudisium1,
            'totalYudisium2' => $totalYudisium2,
            'totalYudisium3' => $totalYudisium3,
            'selesai' => $selesai,
            'dalamProgres' => $dalamProgres,
            'totalKotaUji' => $totalKotaUji,
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

        $kotaList = \DB::table('tbl_yudisium')
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