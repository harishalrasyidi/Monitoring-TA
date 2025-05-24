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
    public function show($id_kota)
    {
        $kota = KotaModel::where('id_kota', $id_kota)->firstOrFail();

        $laporanTA = KotaHasArtefakModel::where('id_kota', $id_kota)
            ->where('id_artefak', 7) // ID Laporan TA
            ->first();

        $posterTA = KotaHasArtefakModel::where('id_kota', $id_kota)
            ->where('id_artefak', 8) // ID Poster TA
            ->first();

        $abstrakTA = KotaHasArtefakModel::where('id_kota', $id_kota)
            ->where('id_artefak', 9) // ✅ GANTI sesuai ID artefak untuk Abstrak
            ->whereNotNull('teks_pengumpulan')
            ->latest('created_at')
            ->first();

        $penulis = User::join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
            ->where('tbl_kota_has_user.id_kota', $id_kota)
            ->where('users.role', 3)
            ->get(['users.name', 'users.nomor_induk']);

        $pembimbing = User::join('tbl_kota_has_user', 'users.id', '=', 'tbl_kota_has_user.id_user')
            ->where('tbl_kota_has_user.id_kota', $id_kota)
            ->where('users.role', 2)
            ->get(['users.name', 'users.nomor_induk']);

        $filePath = null;
        $posterPath = null;

        if ($laporanTA && $laporanTA->file_pengumpulan) {
            $possiblePaths = [
                'submissions/' . $laporanTA->file_pengumpulan,
                'public/submissions/' . $laporanTA->file_pengumpulan,
                $laporanTA->file_pengumpulan
            ];
            foreach ($possiblePaths as $path) {
                if (Storage::exists($path)) {
                    $filePath = $path;
                    break;
                }
            }
        }

        if ($posterTA && $posterTA->file_pengumpulan) {
            $possiblePosterPaths = [
                'submissions/' . $posterTA->file_pengumpulan,
                'public/submissions/' . $posterTA->file_pengumpulan,
                $posterTA->file_pengumpulan
            ];
            foreach ($possiblePosterPaths as $path) {
                if (Storage::exists($path)) {
                    $posterPath = $path;
                    break;
                }
            }
        }

        $laporan = [
            'judul' => $kota->judul,
            'penulis' => $penulis->pluck('name')->implode(', '),
            'tahun' => $kota->periode,
            'program_studi' => $kota->kelas,
            'pembimbing' => $pembimbing->pluck('name')->implode(', '),
            'penguji' => null,
            'kata_kunci' => null,
            'file_path' => $filePath,
            'poster_path' => $posterPath,
            'original_file_name' => $laporanTA ? $laporanTA->file_pengumpulan : null,
            'abstrak' => $abstrakTA ? $abstrakTA->teks_pengumpulan : null,
        ];

        return view('katalog.detail', compact('laporan', 'penulis', 'pembimbing'));
    }

public function index(Request $request)
{
    $search = $request->input('search');
    $katalog = KotaModel::query();

    if ($search) {
        $words = array_filter(explode(' ', $search), function ($word) {
            return strlen($word) >= 3;
        });

        if (count($words) >= 3) {
            $booleanQuery = implode(' ', array_map(function ($word) {
                return '+' . $word;
            }, $words));

            $katalog->whereIn('id_kota', function ($query) use ($booleanQuery) {
                $query->select('id_kota')
                      ->from('tbl_kota_has_artefak')
                      ->whereRaw("MATCH(teks_pengumpulan) AGAINST(? IN BOOLEAN MODE)", [$booleanQuery]);
            });
        } else {
            // Handle kasus ketika kurang dari 3 kata kunci
            $katalog->whereRaw('0 = 1'); // Tidak mengembalikan hasil
        }
    }

    $katalogList = $katalog->paginate(10);

    return view('katalog.index', compact('katalogList'));
}
}
