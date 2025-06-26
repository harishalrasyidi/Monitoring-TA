<?php
namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\KotaHasUserModel;
use App\Models\KotaHasPenguji;
use Illuminate\Http\Request;

class DosbingDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $kotaIdsBimbingan = KotaHasUserModel::where('id_user', $user->id)
            ->pluck('id_kota')
            ->toArray();
        $totalKota = count($kotaIdsBimbingan);
        $kotaIdsUji = KotaHasPenguji::where('id_user', $user->id)
            ->pluck('id_kota')
            ->toArray();
        $queryUji = Kota::whereIn('id_kota', $kotaIdsUji);
        if ($request->filled('periode')) {
            $queryUji->where('periode', $request->periode);
        }
        if ($request->filled('kelas')) {
            $queryUji->where('kelas', $request->kelas);
        }
        $totalKotaUji = $queryUji->count();
        $query = Kota::with(['tahapanProgress.masterTahapan'])
            ->whereIn('id_kota', $kotaIdsBimbingan);
        $allKota = $query->get();
        $tahapanNames = ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'];
        $chartData = array_fill_keys($tahapanNames, 0);
        $selesai = 0;
        $dalamProgres = 0;
        foreach ($allKota as $kota) {
            $tahapanProgress = $kota->tahapanProgress->sortBy('id_master_tahapan_progres');
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
            if ($tahapanProgress->isEmpty()) {
                $dalamProgres++;
                continue;
            }
            $sidangProgress = $tahapanProgress->firstWhere('id_master_tahapan_progres', 4);
            if ($sidangProgress && $sidangProgress->status === 'selesai') {
                $selesai++;
            } else {
                $dalamProgres++;
            }
        }
        $kotaList = $query->paginate(10);
        $periodes = Kota::whereIn('id_kota', $kotaIdsBimbingan)
            ->select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode');
        $kelasList = Kota::whereIn('id_kota', $kotaIdsBimbingan)
            ->select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $chartDataJumlah = array_values($chartData);
        $chartDataPersen = [];
        foreach ($chartData as $key => $val) {
            $chartDataPersen[] = $totalKota > 0 ? round(($val / $totalKota) * 100, 1) : 0;
        }
        return view('beranda.pembimbing.home', [
            'kotaList' => $kotaList,
            'totalKota' => $totalKota,
            'totalKotaUji' => $totalKotaUji,
            'selesai' => $selesai,
            'dalamProgres' => $dalamProgres,
            'chartData' => $chartData,
            'chartDataJumlah' => $chartDataJumlah,
            'chartDataPersen' => $chartDataPersen,
            'periodes' => $periodes,
            'kelasList' => $kelasList,
        ]);
    }
} 