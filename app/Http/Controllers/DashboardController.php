<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use App\Models\KotaTahapanProgress;
use App\Models\MasterTahapanProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Fetch KoTA with progress
            $kotaList = Kota::with(['tahapanProgress'])->get();
            $totalKota = Kota::count();

            // Chart data with static IDs
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

            // Calculate status counts
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

            \Log::info('Chart Data:', $chartData);
            \Log::info('Status Counts:', [
                'total' => $totalKota,
                'selesai' => $selesai,
                'dalamProgres' => $dalamProgres,
            ]);

            return view('beranda.koordinator.home', [
                'kotaList' => $kotaList,
                'totalKota' => $totalKota,
                'selesai' => $selesai,
                'dalamProgres' => $dalamProgres,
                'chartData' => $chartData
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
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
        }
    }
}
