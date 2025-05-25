<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use App\Models\KotaTahapanProgress;
use App\Models\MasterTahapanProgress;
use App\Models\KotaHasResumeBimbinganModel;
use App\Models\KotaHasPenguji;
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
                $kotaIdsBimbingan = KotaHasResumeBimbinganModel::where('id_user', $user->id)
                    ->pluck('id_kota')
                    ->toArray();
                
                $totalKota = count($kotaIdsBimbingan);

                $kotaIdsUji = KotaHasPenguji::where('id_user', $user->id)
                    ->pluck('id_kota')
                    ->toArray();
                
                $totalKotaUji = count($kotaIdsUji);
                
                $tahapanNames = ['Seminar 1', 'Seminar 2', 'Seminar 3', 'Sidang'];
                $tahapanIds = [1, 2, 3, 4];
                $chartData = [];
                
                foreach ($tahapanIds as $index => $tahapanId) {
                    $count = KotaTahapanProgress::where('id_master_tahapan_progres', $tahapanId)
                        ->where('status', 'tuntas')
                        ->whereIn('id_kota', $kotaIdsBimbingan)
                        ->distinct('id_kota')
                        ->count('id_kota');
                    $chartData[$tahapanNames[$index]] = $count;
                }
                
                // Hitung selesai dan dalam progres dari SEMUA data (tidak tergantung pagination)
                $selesai = 0;
                $dalamProgres = 0;

                $allKotaForCount = Kota::with(['tahapanProgress'])
                    ->whereIn('id_kota', $kotaIdsBimbingan)
                    ->get(); // Ambil semua data untuk perhitungan
                
                foreach ($allKotaForCount as $kota) {
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

                // Ambil data untuk pagination (terpisah dari perhitungan)
                $kotaList = Kota::with(['tahapanProgress'])
                    ->whereIn('id_kota', $kotaIdsBimbingan)
                    ->paginate(10);
                
                return view('beranda.pembimbing.home', [
                    'kotaList' => $kotaList,
                    'totalKota' => $totalKota,
                    'totalKotaUji' => $totalKotaUji,
                    'selesai' => $selesai,
                    'dalamProgres' => $dalamProgres,
                    'chartData' => $chartData
                ]);
            }
            
            if ($user->role == 1) {
                // Logika hanya untuk Koordinator
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
                
                // Hitung selesai dan dalam progres dari SEMUA data (tidak tergantung pagination)
                $selesai = 0;
                $dalamProgres = 0;
                
                $allKotaForCount = Kota::with(['tahapanProgress'])->get(); // Ambil semua data untuk perhitungan
                
                foreach ($allKotaForCount as $kota) {
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

                // Ambil data untuk pagination (terpisah dari perhitungan)
                $kotaList = Kota::with(['tahapanProgress'])->paginate(10);
                
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
                    'totalKotaUji' => 0,
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

    public function getKotaUji(Request $request)
    {
        $user = auth()->user();
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        
        $kotaIdsUji = KotaHasPenguji::where('id_user', $user->id)
            ->pluck('id_kota')
            ->toArray();
        
        $query = Kota::with(['tahapanProgress'])
            ->whereIn('id_kota', $kotaIdsUji);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_kota', 'like', "%{$search}%")
                ->orWhere('judul', 'like', "%{$search}%");
            });
        }
        
        $kotaList = $query->paginate($perPage);
        
        return response()->json([
            'data' => $kotaList->items(),
            'pagination' => [
                'current_page' => $kotaList->currentPage(),
                'last_page' => $kotaList->lastPage(),
                'per_page' => $kotaList->perPage(),
                'total' => $kotaList->total()
            ]
        ]);
    }
}