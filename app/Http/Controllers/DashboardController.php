<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use App\Models\KotaTahapanProgress;
use App\Models\MasterTahapanProgress;
use App\Models\KotaHasUserModel;
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

            if ($user->role == 3) {
                $kotaIds = KotaHasUserModel::where('id_user', $user->id)
                    ->pluck('id_kota');

                $anggotaKelompok = DB::table('users')
                    ->join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
                    ->whereIn('tbl_kota_has_user.id_kota', $kotaIds)
                    ->where('users.role', 3)
                    ->select('users.*')
                    ->get();
                
                $dosbing = DB::table('users')
                    ->join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
                    ->whereIn('tbl_kota_has_user.id_kota', $kotaIds)
                    ->where('users.role', 2)
                    ->select('users.*')
                    ->get();

                $penguji = DB::table('users')
                    ->join('tbl_kota_has_penguji', 'users.id', '=', 'tbl_kota_has_penguji.id_user')
                    ->whereIn('tbl_kota_has_penguji.id_kota', $kotaIds)
                    ->select('users.*')
                    ->get();
                
                // Daftar artefak tiap seminar
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

            // Ambil artefak mahasiswa berdasarkan kota yang diikutinya
            $seminar1 = DB::table('tbl_kota_has_artefak as kha')
                ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
                ->whereIn('kha.id_kota', $kotaIds)
                ->whereIn('a.nama_artefak', $seminar1Artefak)
                ->select('a.nama_artefak', 'kha.*')
                ->get();

            $seminar2 = DB::table('tbl_kota_has_artefak as kha')
                ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
                ->whereIn('kha.id_kota', $kotaIds)
                ->whereIn('a.nama_artefak', $seminar2Artefak)
                ->select('a.nama_artefak', 'kha.*')
                ->get();

            $seminar3 = DB::table('tbl_kota_has_artefak as kha')
                ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
                ->whereIn('kha.id_kota', $kotaIds)
                ->whereIn('a.nama_artefak', $seminar3Artefak)
                ->select('a.nama_artefak', 'kha.*')
                ->get();

            $sidang = DB::table('tbl_kota_has_artefak as kha')
                ->join('tbl_artefak as a', 'kha.id_artefak', '=', 'a.id_artefak')
                ->whereIn('kha.id_kota', $kotaIds)
                ->whereIn('a.nama_artefak', $sidangArtefak)
                ->select('a.nama_artefak', 'kha.*')
                ->get();            

                return view('beranda.mahasiswa.home', [
                    'anggotaKelompok' => $anggotaKelompok,
                    'dosbing' => $dosbing,
                    'penguji' => $penguji,
                    'seminar1' => $seminar1,
                    'seminar2' => $seminar2,
                    'seminar3' => $seminar3,
                    'sidang' => $sidang,
                ]);

            }

            
            if ($user->role == 2) {
                $kotaIdsBimbingan = KotaHasUserModel::where('id_user', $user->id)
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