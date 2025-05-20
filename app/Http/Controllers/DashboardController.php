<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\MasterTahapanProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Mengambil data KoTA dengan relasi tahapan progress
            $kotaList = Kota::with(['tahapanProgress.masterTahapan'])->get();
            
            // Menghitung statistik
            $totalKota = Kota::count();
            
            // Mengambil data untuk chart
            $masterTahapan = MasterTahapanProgress::all();
            $chartData = [];
            
            foreach ($masterTahapan as $tahapan) {
                $chartData[$tahapan->nama_progres] = Kota::whereHas('tahapanProgress', function($query) use ($tahapan) {
                    $query->where('id_master_tahapan_progres', $tahapan->id)
                          ->where('status', 'Tuntas');
                })->count();
            }

            // Menghitung status berdasarkan tahapan terakhir
            $selesai = 0;
            $dalamProgres = 0;
            $terlambat = 0;

            foreach ($kotaList as $kota) {
                $lastProgress = $kota->tahapanProgress()
                                   ->orderBy('id_master_tahapan_progres', 'desc')
                                   ->first();
                
                if ($lastProgress) {
                    if ($lastProgress->status === 'Tuntas' && $lastProgress->masterTahapan->nama_progres === 'Sidang') {
                        $selesai++;
                    } elseif ($lastProgress->status === 'Belum Tuntas') {
                        $terlambat++;
                    } else {
                        $dalamProgres++;
                    }
                }
            }

            // Debug: Cek apakah data ada
            \Log::info('Kota List:', ['count' => $kotaList->count()]);
            \Log::info('Chart Data:', $chartData);
            \Log::info('Status Counts:', [
                'total' => $totalKota,
                'selesai' => $selesai,
                'dalamProgres' => $dalamProgres,
                'terlambat' => $terlambat
            ]);

            return view('beranda.koordinator.home', [
                'kotaList' => $kotaList,
                'totalKota' => $totalKota,
                'selesai' => $selesai,
                'dalamProgres' => $dalamProgres,
                'terlambat' => $terlambat,
                'chartData' => $chartData
            ]);

        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return view('beranda.koordinator.home', [
                'kotaList' => collect([]),
                'totalKota' => 0,
                'selesai' => 0,
                'dalamProgres' => 0,
                'terlambat' => 0,
                'chartData' => []
            ]);
        }
    }
} 