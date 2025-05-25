<?php

namespace App\Http\Controllers;

use App\Models\KotaModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $proposal_id = 7; // ID artefak proposal
        $poster_id = 8; // ID artefak poster

        // Data untuk filter dropdown
        $categories = KotaModel::select('kategori')->distinct()->whereNotNull('kategori')->get();
        $years = KotaModel::selectRaw('YEAR(periode) as year')->distinct()->orderBy('year', 'desc')->get();
        $prodis = KotaModel::select('prodi')->distinct()->get();
        $metodologis = KotaModel::select('metodologi')->distinct()->whereNotNull('metodologi')->where('metodologi', '!=', '')->get();
        $dosens = User::where('role', 2)->get(); // Dosen pembimbing

        // Query utama
        $query = KotaModel::query()
            ->whereExists(function ($subQuery) use ($proposal_id) {
                $subQuery->select(DB::raw(1))
                    ->from('tbl_kota_has_artefak')
                    ->whereColumn('tbl_kota_has_artefak.id_kota', 'tbl_kota.id_kota')
                    ->where('tbl_kota_has_artefak.id_artefak', $proposal_id);
            });

        // Jika mencari
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $searchWords = explode(' ', trim($searchTerm));

            $validWords = array_filter($searchWords, function ($word) {
                return strlen(trim($word)) >= 3;
            });

            if (count($validWords) >= 3 || (count($validWords) === 1 && strlen($validWords[0]) >= 5)) {
                $query->where(function ($q) use ($validWords, $searchTerm) {
                    $q->where('judul', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('abstrak', 'LIKE', "%{$searchTerm}%");

                    foreach ($validWords as $word) {
                        $q->orWhere('judul', 'LIKE', "%{$word}%")
                            ->orWhere('kategori', 'LIKE', "%{$word}%")
                            ->orWhere('metodologi', 'LIKE', "%{$word}%")
                            ->orWhere('abstrak', 'LIKE', "%{$word}%");
                    }
                });
            }
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter prodi
        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        // Filter metodologi
        if ($request->filled('metodologi')) {
            $query->where('metodologi', $request->metodologi);
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->whereYear('periode', $request->tahun);
        }

        // Filter dosen pembimbing
        if ($request->filled('dosen')) {
            $query->whereExists(function ($subQuery) use ($request) {
                $subQuery->select(DB::raw(1))
                    ->from('tbl_kota_has_user')
                    ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
                    ->whereColumn('tbl_kota_has_user.id_kota', 'tbl_kota.id_kota')
                    ->where('users.id', $request->dosen)
                    ->where('users.role', 2);
            });
        }

        // Dapatkan hasil dengan pagination
        $katalog = $query->paginate(12);

        // Tambahkan informasi poster untuk setiap item
        foreach ($katalog as $item) {
            $poster = DB::table('tbl_kota_has_artefak')
                ->where('id_kota', $item->id_kota)
                ->where('id_artefak', $poster_id)
                ->select('file_pengumpulan')
                ->first();

            $item->poster_file = $poster ? $poster->file_pengumpulan : null;
        }

        return view('katalog.katalog', compact('prodis', 'katalog', 'categories', 'years', 'metodologis', 'dosens'));
    }
}