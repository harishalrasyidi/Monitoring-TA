<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KotaModel;
use App\Models\ResumeBimbinganModel;
use App\Models\KotaHasTahapanProgresModel;
use App\Models\KotaHasArtefakModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role == '1') {
                return view('beranda.koordinator.home');
            } elseif ($role == '3') {
                $user = auth()->user();

                // Query untuk mendapatkan kota yang terkait dengan mahasiswa
                $kotas = KotaModel::whereHas('users', function ($q) use ($user) {
                    $q->where('id_user', $user->id)->where('role', 3); // Filter hanya role 3 (mahasiswa)
                })->get();
                $dosen = $kotas->flatMap->users->where('role', 2);
                $mahasiswa = $kotas->flatMap->users->where('role', 3);
                // $kotaIds = $kotas->pluck('id'); 
                $id_kota = DB::table('tbl_kota_has_user')
                            ->where('id_user', $user->id)
                            ->value('id_kota');

                // Menghitung progress tahapan bimbingan
                $progressStage1Count = ResumeBimbinganModel::join('tbl_kota_has_resume_bimbingan', 'tbl_resume_bimbingan.id_resume_bimbingan', '=', 'tbl_kota_has_resume_bimbingan.id_resume_bimbingan')
                                                            ->where('tbl_kota_has_resume_bimbingan.id_kota', $id_kota)
                                                            ->where('tahapan_progres', '2')
                                                            ->count();
                $progressStage2Count = ResumeBimbinganModel::join('tbl_kota_has_resume_bimbingan', 'tbl_resume_bimbingan.id_resume_bimbingan', '=', 'tbl_kota_has_resume_bimbingan.id_resume_bimbingan')
                                                            ->where('tbl_kota_has_resume_bimbingan.id_kota', $id_kota)
                                                            ->where('tahapan_progres', '3')
                                                            ->count();
                $progressStage3Count = ResumeBimbinganModel::join('tbl_kota_has_resume_bimbingan', 'tbl_resume_bimbingan.id_resume_bimbingan', '=', 'tbl_kota_has_resume_bimbingan.id_resume_bimbingan')
                                                            ->where('tbl_kota_has_resume_bimbingan.id_kota', $id_kota)
                                                            ->where('tahapan_progres', '4')
                                                            ->count();
                $seminar_1 = 1; // Definisikan id_timeline
                $seminar_2 = 2; // Definisikan id_timeline
                $seminar_3 = 3; // Definisikan id_timeline
                $seminar_4 = 4; // Definisikan id_timeline
                
                $total_kegiatan_1 = DB::table('tbl_kegiatan_has_timeline as kt')
                                    ->join('tbl_jadwal_kegiatan as j', 'kt.id_jadwal_kegiatan', '=', 'j.id')
                                    ->where('kt.id_timeline', $seminar_1)
                                    ->count();
                $selesai_count_1 = DB::table('tbl_kegiatan_has_timeline as kt')
                                    ->join('tbl_jadwal_kegiatan as j', 'kt.id_jadwal_kegiatan', '=', 'j.id')
                                    ->where('kt.id_timeline', $seminar_1)
                                    ->where('j.status', 'completed')
                                    ->count();
                $total_kegiatan_2 = DB::table('tbl_kegiatan_has_timeline as kt')
                                    ->join('tbl_jadwal_kegiatan as j', 'kt.id_jadwal_kegiatan', '=', 'j.id')
                                    ->where('kt.id_timeline', $seminar_2)
                                    ->count();
                $selesai_count_2 = DB::table('tbl_kegiatan_has_timeline as kt')
                                    ->join('tbl_jadwal_kegiatan as j', 'kt.id_jadwal_kegiatan', '=', 'j.id')
                                    ->where('kt.id_timeline', $seminar_2)
                                    ->where('j.status', 'completed')
                                    ->count();
                $total_kegiatan_3 = DB::table('tbl_kegiatan_has_timeline as kt')
                                    ->join('tbl_jadwal_kegiatan as j', 'kt.id_jadwal_kegiatan', '=', 'j.id')
                                    ->where('kt.id_timeline', $seminar_3)
                                    ->count();
                $selesai_count_3 = DB::table('tbl_kegiatan_has_timeline as kt')
                                    ->join('tbl_jadwal_kegiatan as j', 'kt.id_jadwal_kegiatan', '=', 'j.id')
                                    ->where('kt.id_timeline', $seminar_3)
                                    ->where('j.status', 'completed')
                                    ->count();
                $total_kegiatan_4 = DB::table('tbl_kegiatan_has_timeline as kt')
                                    ->join('tbl_jadwal_kegiatan as j', 'kt.id_jadwal_kegiatan', '=', 'j.id')
                                    ->where('kt.id_timeline', $seminar_4)
                                    ->count();
                $selesai_count_4 = DB::table('tbl_kegiatan_has_timeline as kt')
                                    ->join('tbl_jadwal_kegiatan as j', 'kt.id_jadwal_kegiatan', '=', 'j.id')
                                    ->where('kt.id_timeline', $seminar_4)
                                    ->where('j.status', 'completed')
                                    ->count();
        
                // Hitung persentase
                $selesaiPercentage1 = ($total_kegiatan_1 > 0) ? ($selesai_count_1 / $total_kegiatan_1) * 100 : 0;
                $selesaiPercentage2 = ($total_kegiatan_2 > 0) ? ($selesai_count_2 / $total_kegiatan_2) * 100 : 0;
                $selesaiPercentage3 = ($total_kegiatan_3 > 0) ? ($selesai_count_3 / $total_kegiatan_3) * 100 : 0;
                $selesaiPercentage4 = ($total_kegiatan_4 > 0) ? ($selesai_count_4 / $total_kegiatan_4) * 100 : 0;

                // Menyiapkan data artefak untuk ditampilkan
                $masterArtefaks = DB::table('tbl_master_artefak')->get();
                $artefakKota = KotaHasArtefakModel::where('id_kota', $id_kota)
                                                    ->join('tbl_artefak', 'tbl_kota_has_artefak.id_artefak', '=', 'tbl_artefak.id_artefak')
                                                    ->select('tbl_artefak.nama_artefak')
                                                    ->get();
                $tahapan_progres = KotaHasTahapanProgresModel::where('id_kota',$id_kota)->get();

                // Inisialisasi array kosong untuk menyimpan artefak sesuai dengan tahapan
                $seminar1 = [];
                $seminar2 = [];
                $seminar3 = [];
                $sidang = [];

                // Looping untuk membagi artefak sesuai dengan tahapan
                foreach ($masterArtefaks as $artefak) {
                    switch ($artefak->nama_artefak) {
                        case 'FTA 01':
                        case 'FTA 02':
                        case 'FTA 03':
                        case 'FTA 04':
                        case 'FTA 05':
                        case 'FTA 05a':
                        case 'Proposal Tugas Akhir':
                            $seminar1[] = $artefak;
                            break;
                        case 'FTA 06':
                        case 'FTA 06a':
                        case 'FTA 07':
                        case 'FTA 08':
                        case 'FTA 09':
                        case 'FTA 09a':
                        case 'Laporan Tugas Akhir':
                        case 'SRS':
                        case 'SDD':
                            $seminar2[] = $artefak;
                            break;
                        case 'FTA 10':
                        case 'FTA 11':
                        case 'FTA 12':
                        case 'Laporan Tugas Akhir':
                        case 'SRS':
                        case 'SDD':
                            $seminar3[] = $artefak;
                            break;
                        case 'FTA 13':
                        case 'FTA 14':
                        case 'FTA 15':
                        case 'FTA 16':
                        case 'FTA 17':
                        case 'FTA 18':
                        case 'FTA 19':
                        case 'Laporan Tugas Akhir':
                        case 'SRS':
                        case 'SDD':
                            $sidang[] = $artefak;
                            break;
                        default:
                            break;
                    }
                }
                $mastertahapan = DB::table('tbl_master_tahapan_progres')->get();


                return view('beranda.mahasiswa.home', compact('kotas', 'progressStage1Count', 'progressStage2Count', 'progressStage3Count', 'dosen', 'mahasiswa', 'seminar1', 'seminar2', 'seminar3', 'sidang', 'artefakKota','tahapan_progres', 'selesaiPercentage1', 'selesaiPercentage2', 'selesaiPercentage3', 'selesaiPercentage4', 'mastertahapan'));
            }
        }

        $query = KotaModel::query();
        $user = auth()->user();
    
        if ($user->role == 2) {
            // Query untuk KoTA yang dibimbing
            $queryKota = KotaModel::query()
                ->join('tbl_kota_has_user', 'tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
                ->leftJoin('tbl_kota_has_tahapan_progres', 'tbl_kota.id_kota', '=', 'tbl_kota_has_tahapan_progres.id_kota')
                ->leftJoin('tbl_master_tahapan_progres', 'tbl_kota_has_tahapan_progres.id_master_tahapan_progres', '=', 'tbl_master_tahapan_progres.id')
                ->where('tbl_kota_has_user.id_user', $user->id)
                ->select('tbl_kota.*', 'tbl_master_tahapan_progres.nama_progres AS nama_tahapan')
                ->where(function ($query) {
                    $query->where('tbl_kota_has_tahapan_progres.status', 'on_progres')
                        ->orWhere('tbl_kota_has_tahapan_progres.status', 'disetujui');
                });

            // Query untuk KoTA yang diuji
            $queryKotaDiuji = KotaModel::query()
                ->join('tbl_kota_has_penguji', 'tbl_kota.id_kota', '=', 'tbl_kota_has_penguji.id_kota')
                ->leftJoin('tbl_kota_has_tahapan_progres', 'tbl_kota.id_kota', '=', 'tbl_kota_has_tahapan_progres.id_kota')
                ->leftJoin('tbl_master_tahapan_progres', 'tbl_kota_has_tahapan_progres.id_master_tahapan_progres', '=', 'tbl_master_tahapan_progres.id')
                ->where('tbl_kota_has_penguji.id_user', $user->id)
                ->select('tbl_kota.*', 'tbl_master_tahapan_progres.nama_progres AS nama_tahapan')
                ->where(function ($query) {
                    $query->where('tbl_kota_has_tahapan_progres.status', 'on_progres')
                        ->orWhere('tbl_kota_has_tahapan_progres.status', 'disetujui');
                });

            // Filter berdasarkan parameter
            if ($request->has('sort') && $request->has('value')) {
                $sort = $request->input('sort');
                $value = $request->input('value');
                $queryKota->where($sort, $value);
                $queryKotaDiuji->where($sort, $value);
            }

            // Sorting
            if ($request->has('sort') && $request->has('direction')) {
                $queryKota->orderBy($request->input('sort'), $request->input('direction'));
                $queryKotaDiuji->orderBy($request->input('sort'), $request->input('direction'));
            }

            $kotas = $queryKota->paginate(10);
            $kotas_diuji = $queryKotaDiuji->paginate(10);

            $availableYears = DB::table('tbl_kota')
                ->join('tbl_kota_has_user', 'tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
                ->where('tbl_kota_has_user.id_user', Auth::id())
                ->distinct()
                ->orderBy('tbl_kota.periode', 'desc')
                ->pluck('tbl_kota.periode');

            return view('beranda.pembimbing.home', compact('kotas', 'kotas_diuji', 'availableYears'));
        } elseif ($user->role == 4) {
            return view('beranda.kaprodi.home');
        }
    }

    public function kota_status(Request $request)
    {
        $status = $request->input('status');
        $id_kota = $request->input('id_kota');
        $id_master_tahapan_progres = $request->input('id_master_tahapan_progres');
    
        // Cari tahapan progres saat ini
        $kotaTahapanProgres = KotaHasTahapanProgresModel::where('id_kota', $id_kota)
            ->where('id_master_tahapan_progres', $id_master_tahapan_progres)
            ->first();
            
        if ($kotaTahapanProgres) {
            // Ubah status tahapan progres saat ini
            $kotaTahapanProgres->status = $status;
            $kotaTahapanProgres->save();
    
            // Jika statusnya 'selesai', ubah status data setelahnya menjadi 'on_progres'
            if ($status == 'selesai') {
                $nextTahapanProgres = KotaHasTahapanProgresModel::where('id_kota', $id_kota)
                                                                ->where('id_master_tahapan_progres', $id_master_tahapan_progres + 1)
                                                                ->first();
    
                if ($nextTahapanProgres) {
                    $nextTahapanProgres->status = 'on_progres';
                    $nextTahapanProgres->save();
                }
            }
        }
    
        return redirect()->back();
    }

    public function showFile($nama_artefak)
    {
        $artefak = DB::table('tbl_kota_has_artefak')
                        ->join('tbl_artefak', 'tbl_kota_has_artefak.id_artefak', '=', 'tbl_artefak.id_artefak')
                        ->where('tbl_artefak.nama_artefak', $nama_artefak)
                        ->select('tbl_kota_has_artefak.file_pengumpulan', 'tbl_kota_has_artefak.id_kota')
                        ->first();

        // Ambil path file dari database
        $filePath = $artefak->file_pengumpulan;
        $idKota = $artefak->id_kota;

        // Periksa apakah file ada
        if (Storage::disk('public')->exists($filePath)) {
            // Redirect ke URL file
            return response()->file(storage_path('app/public/' . $filePath));
        } else {
            $user = auth()->user();
            if($user->role == 3) {
                return redirect()->route('home')->with('error', 'File tidak ditemukan');
            } else {
                return redirect()->route('kota.detail', ['id' => $idKota])->with('error', 'File tidak ditemukan');
            }
        }
    }
}
