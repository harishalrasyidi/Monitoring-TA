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
        // Data untuk filter dropdown
        $categories = KotaModel::select('kategori')->distinct()->get();
        $years = KotaHasArtefakModel::selectRaw('YEAR(waktu_pengumpulan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();
        $prodis = KotaModel::select('prodi')->distinct()->get();

        // Query utama
        $query = KotaHasArtefakModel::query()
            ->join('tbl_artefak', 'tbl_kota_has_artefak.id_artefak', '=', 'tbl_artefak.id_artefak')
            ->join('tbl_kota', 'tbl_kota_has_artefak.id_kota', '=', 'tbl_kota.id_kota')
            ->select(
                'tbl_kota_has_artefak.*',
                'tbl_artefak.nama_artefak',
                'tbl_artefak.deskripsi',
                'tbl_kota.nama_kota',
                'tbl_kota.judul',
                'tbl_kota.kelas',
                'tbl_kota.periode',
                'tbl_kota.kategori',
                'tbl_kota.prodi',
                'tbl_kota_has_artefak.teks_pengumpulan' // <--- penting
            );

        // Jika mencari
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $searchWords = explode(' ', trim($searchTerm));

            // Hanya kata minimal 3 huruf
            $validWords = array_filter($searchWords, function ($word) {
                return strlen(trim($word)) >= 3;
            });

            if (count($validWords) >= 3 || (count($validWords) === 1 && strlen($validWords[0]) >= 5)) {
                $query->where(function ($q) use ($validWords, $searchTerm) {
                    // full phrase match
                    $q->where('tbl_kota.judul', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('tbl_kota_has_artefak.teks_pengumpulan', 'LIKE', "%{$searchTerm}%");

                    // kata per kata
                    foreach ($validWords as $word) {
                        $q->orWhere('tbl_kota.judul', 'LIKE', "%{$word}%")
                          ->orWhere('tbl_kota.nama_kota', 'LIKE', "%{$word}%")
                          ->orWhere('tbl_kota.kategori', 'LIKE', "%{$word}%")
                          ->orWhere('tbl_artefak.nama_artefak', 'LIKE', "%{$word}%")
                          ->orWhere('tbl_artefak.deskripsi', 'LIKE', "%{$word}%")
                          ->orWhere('tbl_kota_has_artefak.teks_pengumpulan', 'LIKE', "%{$word}%"); // <--- penting
                    }

                    // Search nama penulis
                    $q->orWhereExists(function ($authorQuery) use ($validWords, $searchTerm) {
                        $authorQuery->select(DB::raw(1))
                            ->from('tbl_kota_has_user')
                            ->join('users', 'tbl_kota_has_user.id_user', '=', 'users.id')
                            ->whereColumn('tbl_kota_has_user.id_kota', 'tbl_kota.id_kota')
                            ->where('users.role', 3)
                            ->where(function ($nameQuery) use ($validWords, $searchTerm) {
                                $nameQuery->where('users.name', 'LIKE', "%{$searchTerm}%");
                                foreach ($validWords as $word) {
                                    $nameQuery->orWhere('users.name', 'LIKE', "%{$word}%");
                                }
                            });
                    });
                });
            }
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('tbl_kota.kategori', $request->kategori);
        }

        // Filter prodi
        if ($request->filled('prodi')) {
            $query->where('tbl_kota.prodi', $request->prodi);
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tbl_kota_has_artefak.waktu_pengumpulan', $request->tahun);
        }

        // Ambil hasil
        $katalog = $query->orderBy('tbl_kota_has_artefak.waktu_pengumpulan', 'desc')->paginate(12);

        return view('katalog.katalog', compact('katalog', 'categories', 'years', 'prodis'));
    }
}
