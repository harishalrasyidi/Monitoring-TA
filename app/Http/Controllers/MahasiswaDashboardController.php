<?php
namespace App\Http\Controllers;

use App\Models\KotaHasUserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
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
} 