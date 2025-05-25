<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KotaHasArtefakModel;
use App\Models\ArtefakModel;
use App\Models\KotaModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $proposal_id = 7;
        $poster_id = 22; // ID artefak untuk Poster

        // Dapatkan semua data untuk opsi dropdown
        $categories = KotaModel::select('kategori')->distinct()->whereNotNull('kategori')->get();
        $years = KotaHasArtefakModel::selectRaw('YEAR(waktu_pengumpulan) as year')
            ->where('id_artefak', $proposal_id) // Filter berdasarkan artefak ID 7
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();
        
        // Ambil metodologi dari KotaModel untuk prodi D4
        $metodologis = KotaModel::select('metodologi')
            ->distinct()
            ->whereNotNull('metodologi')
            ->where('metodologi', '!=', '')
            ->get();
        
        // Ambil dosen pembimbing (role = 2)
        $dosens = User::where('role', 2)->get();

        // Query dasar dengan join untuk mendapatkan informasi lebih lengkap
        $query = KotaHasArtefakModel::query()
            ->join('tbl_artefak', 'tbl_kota_has_artefak.id_artefak', '=', 'tbl_artefak.id_artefak')
            ->join('tbl_kota', 'tbl_kota_has_artefak.id_kota', '=', 'tbl_kota.id_kota')
            ->leftJoin('tbl_kota_has_user', 'tbl_kota.id_kota', '=', 'tbl_kota_has_user.id_kota')
            ->leftJoin('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
            ->select('tbl_kota_has_artefak.*', 'tbl_artefak.nama_artefak', 'tbl_artefak.deskripsi', 
                    'tbl_kota.nama_kota', 'tbl_kota.judul', 'tbl_kota.kelas', 'tbl_kota.periode', 
                    'tbl_kota.kategori', 'tbl_kota.prodi', 'tbl_kota.topik', 'tbl_kota.metodologi')
            ->where('tbl_kota_has_artefak.id_artefak', $proposal_id) // Filter hanya artefak dengan ID 7
            ->distinct(); // Gunakan distinct untuk menghindari duplikasi

        // Filter berdasarkan topik (search text yang lebih abstrak)
        if ($request->filled('topik')) {
            $topik = $request->topik;
            $query->where(function($q) use ($topik) {
                $q->where('tbl_kota.judul', 'like', '%' . $topik . '%')
                  ->orWhere('tbl_kota.topik', 'like', '%' . $topik . '%')
                  ->orWhere('tbl_artefak.deskripsi', 'like', '%' . $topik . '%');
            });
        }

        // Filter berdasarkan kategori (Riset atau Development untuk D4)
        if ($request->filled('kategori')) {
            $query->where('tbl_kota.kategori', $request->kategori);
        }

        if ($request->filled('metodologi') && $request->filled('kategori') && $request->kategori == 'Development' && $request->kategori == 'Riset') {
            $query->where('tbl_kota.metodologi', $request->metodologi);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tbl_kota_has_artefak.waktu_pengumpulan', $request->tahun);
        }

        // Filter berdasarkan dosen pembimbing
        if ($request->filled('dosen')) {
            $query->where('users.id', $request->dosen)
                  ->where('users.role', 2); // Pastikan yang dipilih adalah dosen
        }

        // Dapatkan hasil dengan pagination
        $katalog = $query->paginate(12);

        // Tambahkan informasi poster untuk setiap item
        foreach ($katalog as $item) {
            // Cari poster yang sesuai dengan id_kota dan id_artefak poster (ID 22)
            $poster = DB::table('tbl_kota_has_artefak')
                ->where('id_kota', $item->id_kota)
                ->where('id_artefak', $poster_id) // Gunakan ID artefak poster (22)
                ->select('file_pengumpulan')
                ->first();
            
            $item->poster_file = $poster ? $poster->file_pengumpulan : null;
        }

        return view('katalog.katalog', compact('katalog', 'categories', 'years', 'metodologis', 'dosens'));
    }
}