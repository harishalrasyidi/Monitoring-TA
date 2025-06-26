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

        // Ambil kelas yang dipegang koordinator ini
        $kelasKoordinator = \DB::table('tbl_koor_has_kelas')
            ->where('id_user', $user->id)
            ->pluck('kelas')
            ->toArray();

        $query = Kota::with(['tahapanProgress'])->whereIn('kelas', $kelasKoordinator);
        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        $totalKota = $query->count();
        $periodes = Kota::whereIn('kelas', $kelasKoordinator)
            ->select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode');
        $kelasLabels = [
            1 => 'D3 - A',
            2 => 'D3 - B',
            3 => 'D4 - A',
            4 => 'D4 - B',
        ];
        $kelasList = collect($kelasKoordinator);
        $allKota = $query->with(['tahapanProgress.masterTahapan'])->get();
        $tahapanNames = ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'];
        $chartData = array_fill_keys($tahapanNames, 0);
        foreach ($allKota as $kota) {
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
        $perPage = $request->get('per_page', 10);
        $kotaList = $query->paginate($perPage);
        $yudisiumModel = new YudisiumModel();
        $totalYudisium1 = $yudisiumModel->getDistribusiYudisium($request->periode, $request->kelas, null, $kelasKoordinator)
            ->where('kategori_yudisium', 1)->first()->jumlah ?? 0;
        $totalYudisium2 = $yudisiumModel->getDistribusiYudisium($request->periode, $request->kelas, null, $kelasKoordinator)
            ->where('kategori_yudisium', 2)->first()->jumlah ?? 0;
        $totalYudisium3 = $yudisiumModel->getDistribusiYudisium($request->periode, $request->kelas, null, $kelasKoordinator)
            ->where('kategori_yudisium', 3)->first()->jumlah ?? 0;
        $selesai = $query->whereHas('tahapanProgress', function($q) {
            $q->where('status', 'selesai');
        }, '=', 4)->count();
        $dalamProgres = $totalKota - $selesai;
        $totalKotaUji = $query->whereHas('tahapanProgress', function($q) {
            $q->where('status', 'selesai')->whereHas('masterTahapan', function($q) {
                $q->where('nama_progres', 'Sidang');
            });
        })->count();
        $seminar1 = DB::table('tbl_kota_has_artefak as kha')
            ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
            ->whereIn('a.nama_artefak', $seminar1Artefak)
            ->select('a.nama_artefak', 'kha.*')
            ->get();
        $seminar2 = DB::table('tbl_kota_has_artefak as kha')
            ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
            ->whereIn('a.nama_artefak', $seminar2Artefak)
            ->select('a.nama_artefak', 'kha.*')
            ->get();
        $seminar3 = DB::table('tbl_kota_has_artefak as kha')
            ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
            ->whereIn('a.nama_artefak', $seminar3Artefak)
            ->select('a.nama_artefak', 'kha.*')
            ->get();
        $sidang = DB::table('tbl_kota_has_artefak as kha')
            ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
            ->whereIn('a.nama_artefak', $sidangArtefak)
            ->select('a.nama_artefak', 'kha.*')
            ->get();
        return view('beranda.koordinator.home', [
            'kotaList' => $kotaList,
            'totalKota' => $totalKota,
            'chartData' => $chartData,
            'periodes' => $periodes,
            'kelasList' => $kelasList,
            'kelasLabels' => $kelasLabels,
            'totalYudisium1' => $totalYudisium1,
            'totalYudisium2' => $totalYudisium2,
            'totalYudisium3' => $totalYudisium3,
            'selesai' => $selesai,
            'dalamProgres' => $dalamProgres,
            'totalKotaUji' => $totalKotaUji,
        ]);
    }

    //
    public function getKotaByYudisium(Request $request)
    {
        $user = auth()->user();
        $kategori = $request->kategori;
        $periode = $request->periode;
        $kelas = $request->kelas;

        // Ambil kelas yang dipegang koordinator
        $kelasKoordinator = \DB::table('tbl_koor_has_kelas')
            ->where('id_user', $user->id)
            ->pluck('kelas')
            ->toArray();

        $kotaList = \DB::table('tbl_yudisium')
            ->join('tbl_kota', 'tbl_yudisium.id_kota', '=', 'tbl_kota.id_kota')
            ->where('tbl_yudisium.kategori_yudisium', $kategori)
            ->whereIn('tbl_kota.kelas', $kelasKoordinator);

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