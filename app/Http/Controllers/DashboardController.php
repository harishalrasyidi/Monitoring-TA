<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use App\Models\KotaTahapanProgress;
use App\Models\MasterTahapanProgress;
use App\Models\KotaHasResumeBimbinganModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();

            if ($user->role == 2) {
                // Logika untuk Pembimbing
                $kotaList = $this->getKotaForPembimbing($user->id);
                $totalKota = $kotaList->count();

                // Chart data untuk pembimbing
                $tahapanNames = ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'];
                $tahapanIds = [1, 2, 3, 4];
                $chartData = [];
                
                $kotaIds = $kotaList->pluck('id')->toArray();

                foreach ($tahapanIds as $index => $tahapanId) {
                    $count = KotaTahapanProgress::where('id_master_tahapan_progres', $tahapanId)
                        ->whereIn('id_kota', $kotaIds)
                        ->where('status', 'tuntas')
                        ->distinct('id_kota')
                        ->count('id_kota');
                    $chartData[$tahapanNames[$index]] = $count;
                }

                $selesai = 0;
                $dalamProgres = 0;

                foreach ($kotaList as $kota) {
                    $tahapanProgress = $kota->tahapanProgress->sortBy('id_master_tahapan_progres');

                    if ($tahapanProgress->isEmpty()) {
                        $dalamProgres++;
                        continue;
                    }

                    $sidangProgress = $tahapanProgress->firstWhere('id_master_tahapan_progres', 4);

                    if ($sidangProgress && $sidangProgress->status === 'tuntas') {
                        $selesai++;
                    } else {
                        $dalamProgres++;
                    }
                }

                return view('beranda.pembimbing.home', [
                    'kotaList' => $kotaList,
                    'totalKota' => $totalKota,
                    'selesai' => $selesai,
                    'dalamProgres' => $dalamProgres,
                    'chartData' => $chartData
                ]);
            }

            if ($user->role == 1) {
                // Logika hanya untuk Koordinator
                $kotaList = Kota::with(['tahapanProgress'])->get();
                $totalKota = Kota::count();

                // Chart data dengan ID statis
                $tahapanNames = ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'];
                $tahapanIds = [1, 2, 3, 4];
                $chartData = [];

                foreach ($tahapanIds as $index => $tahapanId) {
                    $count = KotaTahapanProgress::where('id_master_tahapan_progres', $tahapanId)
                        ->where('status', 'tuntas')
                        ->distinct('id_kota')
                        ->count('id_kota');
                    $chartData[$tahapanNames[$index]] = $count;
                }

                $selesai = 0;
                $dalamProgres = 0;

                foreach ($kotaList as $kota) {
                    $tahapanProgress = $kota->tahapanProgress->sortBy('id_master_tahapan_progres');

                    if ($tahapanProgress->isEmpty()) {
                        $dalamProgres++;
                        continue;
                    }

                    $sidangProgress = $tahapanProgress->firstWhere('id_master_tahapan_progres', 4);

                    if ($sidangProgress && $sidangProgress->status === 'tuntas') {
                        $selesai++;
                    } else {
                        $dalamProgres++;
                    }
                }
                
                return view('beranda.koordinator.home', [
                    'kotaList' => $kotaList,
                    'totalKota' => $totalKota,
                    'selesai' => $selesai,
                    'dalamProgres' => $dalamProgres,
                    'chartData' => $chartData
                ]);
            }

            // Default jika role bukan 1 atau 2
            return abort(403, 'Akses tidak diizinkan.');
        } catch (\Exception $e) {

            if (auth()->check() && auth()->user()->role == 1) {
                return view('beranda.koordinator.home', [
                    'kotaList' => collect([]),
                    'totalKota' => 0,
                    'selesai' => 0,
                    'dalamProgres' => 0,
                    'chartData' => [
                        'Seminar 1' => 0,
                        'Seminar 2' => 0,
                        'Seminar 3' => 0,
                        'Sidang' => 0
                    ]
                ]);
            } elseif (auth()->check() && auth()->user()->role == 2) {
                return view('beranda.pembimbing.home', [
                    'kotaList' => collect([]),
                    'totalKota' => 0,
                    'selesai' => 0,
                    'dalamProgres' => 0,
                    'chartData' => [
                        'Seminar 1' => 0,
                        'Seminar 2' => 0,
                        'Seminar 3' => 0,
                        'Sidang' => 0
                    ]
                ]);
            }

            return abort(500, 'Terjadi kesalahan.');
        }
    }

    private function getKotaForPembimbing($userId)
    {
        // Ambil ID kota yang dibimbing oleh pembimbing ini
        $kotaIds = KotaHasResumeBimbinganModel::where('id_user', $userId)
            ->pluck('id_kota')
            ->unique()
            ->toArray();

        // Ambil data kota beserta tahapan progress
        return Kota::with(['tahapanProgress'])
            ->whereIn('id', $kotaIds)
            ->get();
    }
}