<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use App\Models\KotaHasResumeBimbinganModel;
use App\Models\KotaHasTahapanProgresModel;
use App\Models\KotaHasArtefakModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function dashboardMahasiswa()
    {
        try {
            // Ambil ID user yang login
            $userId = Auth::id();
            Log::info('User ID login', ['user_id' => $userId]);

            // Cek role mahasiswa (role = 3)
            if (Auth::user()->role != 3) {
                Log::warning('User role tidak sesuai untuk dashboard mahasiswa', [
                    'user_id' => $userId,
                    'role' => Auth::user()->role
                ]);
                return redirect()->route('home')->with('error', 'Akses ditolak. Anda bukan mahasiswa.');
            }

            // Ambil data KoTA yang terkait sama mahasiswa
            $kotas = Kota::whereHas('users', function ($query) use ($userId) {
                $query->where('id_user', $userId)->where('role', 3); // Dari tbl_kota_has_user, role 3
            })->get();
            Log::info('KoTA data', ['kotas' => $kotas->toArray()]);

            // Ambil mahasiswa dan dosen
            $mahasiswa = $kotas->flatMap->users->where('role', 3); // Mahasiswa
            $dosen = $kotas->flatMap->users->where('role', 1); // Dosen (role 1)
            Log::info('Mahasiswa data', ['mahasiswa' => $mahasiswa->toArray()]);
            Log::info('Dosen data', ['dosen' => $dosen->toArray()]);

            // Ambil id_kota untuk user
            $id_kota = DB::table('tbl_kota_has_user')
                ->where('id_user', $userId)
                ->value('id_kota');
            Log::info('ID Kota', ['id_kota' => $id_kota]);

            // Hitung jumlah bimbingan per tahap
            $progressStage1Count = KotaHasResumeBimbinganModel::where('id_kota', $id_kota)
                ->join('tbl_resume_bimbingan', 'tbl_kota_has_resume_bimbingan.id_resume_bimbingan', '=', 'tbl_resume_bimbingan.id_resume_bimbingan')
                ->where('tbl_resume_bimbingan.tahapan_progres', 2) // Seminar 2
                ->count();
            $progressStage2Count = KotaHasResumeBimbinganModel::where('id_kota', $id_kota)
                ->join('tbl_resume_bimbingan', 'tbl_kota_has_resume_bimbingan.id_resume_bimbingan', '=', 'tbl_resume_bimbingan.id_resume_bimbingan')
                ->where('tbl_resume_bimbingan.tahapan_progres', 3) // Seminar 3
                ->count();
            $progressStage3Count = KotaHasResumeBimbinganModel::where('id_kota', $id_kota)
                ->join('tbl_resume_bimbingan', 'tbl_kota_has_resume_bimbingan.id_resume_bimbingan', '=', 'tbl_resume_bimbingan.id_resume_bimbingan')
                ->where('tbl_resume_bimbingan.tahapan_progres', 4) // Sidang
                ->count();
            Log::info('Bimbingan counts', [
                'seminar_2' => $progressStage1Count,
                'seminar_3' => $progressStage2Count,
                'sidang' => $progressStage3Count,
            ]);

            // Ambil tahapan progres
            $tahapan_progres = KotaHasTahapanProgresModel::where('id_kota', $id_kota)->get();
            $mastertahapan = DB::table('tbl_master_tahapan_progres')->get();
            Log::info('Tahapan progres', ['tahapan_progres' => $tahapan_progres->toArray()]);
            Log::info('Master tahapan', ['mastertahapan' => $mastertahapan->toArray()]);

            // Hitung persentase progres berdasarkan kegiatan
            $seminar_1 = 1; // ID timeline Seminar 1
            $seminar_2 = 2; // ID timeline Seminar 2
            $seminar_3 = 3; // ID timeline Seminar 3
            $seminar_4 = 4; // ID timeline Sidang

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
            Log::info('Persentase progres', [
                'seminar_1' => $selesaiPercentage1,
                'seminar_2' => $selesaiPercentage2,
                'seminar_3' => $selesaiPercentage3,
                'sidang' => $selesaiPercentage4,
            ]);

            // Ambil data artefak
            $masterArtefaks = DB::table('tbl_master_artefak')->get();
            $artefakKota = KotaHasArtefakModel::where('id_kota', $id_kota)
                ->join('tbl_artefak', 'tbl_kota_has_artefak.id_artefak', '=', 'tbl_artefak.id_artefak')
                ->select('tbl_artefak.nama_artefak', 'tbl_kota_has_artefak.file_pengumpulan')
                ->get();
            Log::info('Artefak data', ['artefakKota' => $artefakKota->toArray()]);

            // Bagi artefak per tahap
            $seminar1 = [];
            $seminar2 = [];
            $seminar3 = [];
            $sidang = [];

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
            Log::info('Artefak per tahap', [
                'seminar1' => $seminar1,
                'seminar2' => $seminar2,
                'seminar3' => $seminar3,
                'sidang' => $sidang,
            ]);

            return view('beranda.mahasiswa.home', compact(
                'kotas',
                'mahasiswa',
                'dosen',
                'progressStage1Count',
                'progressStage2Count',
                'progressStage3Count',
                'tahapan_progres',
                'mastertahapan',
                'selesaiPercentage1',
                'selesaiPercentage2',
                'selesaiPercentage3',
                'selesaiPercentage4',
                'seminar1',
                'seminar2',
                'seminar3',
                'sidang',
                'artefakKota'
            ));

        } catch (\Exception $e) {
            Log::error('Mahasiswa Dashboard Error: ' . $e->getMessage());
            return view('beranda.mahasiswa.home', [
                'kotas' => collect([]),
                'mahasiswa' => collect([]),
                'dosen' => collect([]),
                'progressStage1Count' => 0,
                'progressStage2Count' => 0,
                'progressStage3Count' => 0,
                'tahapan_progres' => collect([]),
                'mastertahapan' => collect([]),
                'selesaiPercentage1' => 0,
                'selesaiPercentage2' => 0,
                'selesaiPercentage3' => 0,
                'selesaiPercentage4' => 0,
                'seminar1' => [],
                'seminar2' => [],
                'seminar3' => [],
                'sidang' => [],
                'artefakKota' => collect([])
            ])->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}