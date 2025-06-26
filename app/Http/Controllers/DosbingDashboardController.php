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
        return view('beranda.pembimbing.home', [
            'kotaList' => $kotaList,
            'totalKota' => $totalKota,
            'totalKotaUji' => $totalKotaUji,
            'selesai' => $selesai,
            'dalamProgres' => $dalamProgres,
            'chartData' => $chartData,
            'periodes' => $periodes,
            'kelasList' => $kelasList,
        ]);
    }
} 