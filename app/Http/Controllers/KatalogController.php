<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KotaHasArtefakModel;
use App\Models\ArtefakModel;
use App\Models\KotaModel;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        // Dapatkan semua data untuk opsi dropdown
        $categories = KotaModel::select('kategori')->distinct()->get();
        $years = KotaHasArtefakModel::selectRaw('YEAR(waktu_pengumpulan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();
        $prodis = KotaModel::select('prodi')->distinct()->get();

        // Query dasar dengan join untuk mendapatkan informasi lebih lengkap
        $query = KotaHasArtefakModel::query()
            ->join('tbl_artefak', 'tbl_kota_has_artefak.id_artefak', '=', 'tbl_artefak.id_artefak')
            ->join('tbl_kota', 'tbl_kota_has_artefak.id_kota', '=', 'tbl_kota.id_kota')
            ->select('tbl_kota_has_artefak.*', 'tbl_artefak.nama_artefak', 'tbl_artefak.deskripsi', 
                    'tbl_kota.nama_kota', 'tbl_kota.judul', 'tbl_kota.kelas', 'tbl_kota.periode', 'tbl_kota.kategori', 'tbl_kota.prodi');
    
        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('tbl_kota.kategori', $request->kategori);
        }
    
        // Filter berdasarkan prodi
        if ($request->filled('prodi')) {
            $query->where('tbl_kota.prodi', $request->prodi);
        }
    
        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tbl_kota_has_artefak.waktu_pengumpulan', $request->tahun);
        }
    
        // Dapatkan hasil dengan pagination
        $katalog = $query->paginate(12);
    
        return view('katalog.katalog', compact('katalog', 'categories', 'years', 'prodis'));
    }
}