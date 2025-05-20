<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data KoTA dengan relasi tahapan progress
        $kotaList = Kota::with('tahapanProgress')->get();
        
        // Menghitung statistik
        $totalKota = Kota::count();
        $selesai = Kota::where('status', 'Selesai')->count();
        $dalamProgres = Kota::where('status', 'Dalam Progres')->count();
        $terlambat = Kota::where('status', 'Terlambat')->count();

        // Data untuk chart
        $chartData = [
            'seminar1' => Kota::whereHas('tahapanProgress', function($query) {
                $query->where('id_tahapan', 1)->where('status', 'tuntas');
            })->count(),
            'seminar2' => Kota::whereHas('tahapanProgress', function($query) {
                $query->where('id_tahapan', 2)->where('status', 'tuntas');
            })->count(),
            'seminar3' => Kota::whereHas('tahapanProgress', function($query) {
                $query->where('id_tahapan', 3)->where('status', 'tuntas');
            })->count(),
            'sidang' => Kota::whereHas('tahapanProgress', function($query) {
                $query->where('id_tahapan', 4)->where('status', 'tuntas');
            })->count(),
        ];

        return view('beranda.koordinator.home', compact(
            'kotaList',
            'totalKota',
            'selesai',
            'dalamProgres',
            'terlambat',
            'chartData'
        ));
    }
} 