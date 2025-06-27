<?php
namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\KotaHasUserModel;
use App\Models\KotaHasPenguji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosbingDashboardController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    $search = $request->get('search');
    $periode = $request->get('periode');
    $kelas = $request->get('kelas');
    $perPage = $request->get('per_page', 10);

    // Ambil semua kota yang dibimbing dosbing ini
    $kotaIdsBimbingan = KotaHasUserModel::where('id_user', $user->id)
        ->pluck('id_kota')
        ->toArray();

    // =======================
    // Bagian untuk statistik dan grafik
    // =======================
    $queryStatistik = Kota::with(['tahapanProgress.masterTahapan'])
        ->whereIn('id_kota', $kotaIdsBimbingan);

    if ($periode) {
        $queryStatistik->where('periode', $periode);
    }
    if ($kelas) {
        $queryStatistik->where('kelas', $kelas);
    }

    $allFilteredKota = $queryStatistik->get();
    $totalKota = $allFilteredKota->count();

    $tahapanNames = ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'];
    $chartData = array_fill_keys($tahapanNames, 0);
    $selesai = 0;
    $dalamProgres = 0;

    foreach ($allFilteredKota as $kota) {
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

    $chartDataJumlah = array_values($chartData);
    $chartDataPersen = [];
    foreach ($chartData as $val) {
        $chartDataPersen[] = $totalKota > 0 ? round(($val / $totalKota) * 100, 1) : 0;
    }

    // =======================
    // Bagian untuk List KoTA (tabel)
    // =======================
    $queryListKota = Kota::with(['tahapanProgress.masterTahapan'])
        ->whereIn('id_kota', $kotaIdsBimbingan);

    if ($periode) {
        $queryListKota->where('periode', $periode);
    }
    if ($kelas) {
        $queryListKota->where('kelas', $kelas);
    }

    if ($search) {
        $queryListKota->where(function ($q) use ($search) {
            $q->where('nama_kota', 'LIKE', '%' . $search . '%')
              ->orWhere('judul', 'LIKE', '%' . $search . '%');
        });
    }

    $kotaList = $queryListKota->paginate($perPage)->appends($request->query());

    // =======================
    // Total KoTA UJI (penguji)
    // =======================
    $kotaIdsUji = KotaHasPenguji::where('id_user', $user->id)->pluck('id_kota')->toArray();
    $queryUji = Kota::whereIn('id_kota', $kotaIdsUji);
    if ($periode) {
        $queryUji->where('periode', $periode);
    }
    if ($kelas) {
        $queryUji->where('kelas', $kelas);
    }
    $totalKotaUji = $queryUji->count();

    // =======================
    // Data filter (dropdown)
    // =======================
    $periodes = Kota::whereIn('id_kota', $kotaIdsBimbingan)
        ->select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode');
    $kelasList = Kota::whereIn('id_kota', $kotaIdsBimbingan)
        ->select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

    // Timeline
    $timelineData = \DB::table('tbl_timeline')
        ->select('nama_kegiatan as name', 'tanggal_mulai as start', 'tanggal_selesai as end')
        ->orderBy('tanggal_mulai')
        ->get()
        ->toArray();

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
        'timelineData' => $timelineData,
    ]);
}


} 