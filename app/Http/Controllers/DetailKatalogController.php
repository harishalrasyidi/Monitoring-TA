<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KotaModel;
use App\Models\KotaHasUserModel;
use App\Models\KotaHasArtefakModel;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DetailKatalogController extends Controller
{
    /**
     * Display a specific thesis report.
     *
     * @param  int  $id_kota
     * @return \Illuminate\Http\Response
     */
    public function show($id_kota)
    {
        // Get thesis (KoTA) data
        $kota = KotaModel::where('id_kota', $id_kota)->firstOrFail();
        
        // Get thesis report file path
        $laporanTA = KotaHasArtefakModel::where('id_kota', $id_kota)
            ->where('id_artefak', 7) // PDF Laporan TA
            ->first();
            
        // Get poster file path (if needed)
        $posterTA = KotaHasArtefakModel::where('id_kota', $id_kota)
            ->where('id_artefak', 8) // Poster Laporan TA
            ->first();
            
        // Get student authors (role = 3)
        $penulis = User::join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
            ->where('tbl_kota_has_user.id_kota', $id_kota)
            ->where('users.role', 3)
            ->get(['users.name', 'users.nomor_induk']);
            
        // Combine student names
        $namaPenulis = $penulis->pluck('name')->implode(', ');
        
        // Get supervisors (role = 2)
        $pembimbing = User::join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
            ->where('tbl_kota_has_user.id_kota', $id_kota)
            ->where('users.role', 2)
            ->get(['users.name', 'users.nomor_induk']);
            
        // Combine supervisor names
        $namaPembimbing = $pembimbing->pluck('name')->implode(', ');
        
        // Format data for view
        $laporan = [
            'judul' => $kota->judul,
            'penulis' => $namaPenulis,
            'tahun' => $kota->periode,
            'program_studi' => $kota->kelas,
            'pembimbing' => $namaPembimbing,
            'penguji' => null, // If you have examiner data, add it here
            'kata_kunci' => null, // If you have keywords data, add it here
            'file_path' => $laporanTA ? $laporanTA->file_pengumpulan : null,
            'poster_path' => $posterTA ? $posterTA->file_pengumpulan : null,
        ];
        
        // Pass data to the view
        return view('katalog.detail', compact('laporan', 'penulis', 'pembimbing'));
    }
    
    /**
     * Display a list of all thesis reports.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all thesis with report documents
        $laporanList = KotaModel::join('tbl_kota_has_artefak', 'tbl_kota.id_kota', '=', 'tbl_kota_has_artefak.id_kota')
            ->where('tbl_kota_has_artefak.id_artefak', 7) // PDF Laporan TA
            ->select('tbl_kota.*', 'tbl_kota_has_artefak.file_pengumpulan', 'tbl_kota_has_artefak.waktu_pengumpulan')
            ->get();
            
        return view('katalog.index', compact('laporanList'));
    }
}