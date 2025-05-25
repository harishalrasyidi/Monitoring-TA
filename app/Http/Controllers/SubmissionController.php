<?php

namespace App\Http\Controllers;

use App\Models\ArtefakModel;
use App\Models\User;
use App\Models\KotaHasArtefakModel;
use App\Models\KotaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function store(Request $request, $artefak_id)
    {
        // Ambil data artefak untuk mengetahui kategorinya
        $artefak = ArtefakModel::findOrFail($artefak_id);
        
        // Mendapatkan id_kota dari user yang sedang login
        $user = auth()->user();
        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');

        // Pastikan id_kota valid sebelum menyimpan
        if (!$id_kota) {
            return redirect()->route('artefak')->with('error', 'Gagal menyimpan data: id_kota tidak valid.');
        }

        if ($artefak->kategori_artefak == 'Teks') {
            // Validasi untuk kategori Teks (hanya teks_pengumpulan)
            $request->validate([
                'teks_pengumpulan' => 'required|string|min:10',
            ], [
                'teks_pengumpulan.required' => 'Teks pengumpulan harus diisi.',
                'teks_pengumpulan.string' => 'Teks pengumpulan harus berupa teks.',
                'teks_pengumpulan.min' => 'Teks pengumpulan minimal 10 karakter.',
            ]);
            
            // Simpan/hanya update ke tbl_kota
            $kota = KotaModel::findOrFail($id_kota);
            // Ganti kolom sesuai kebutuhan (misal: teks_pengumpulan atau abstrak)
            $kota->abstrak = $request->input('teks_pengumpulan');
            $kota->save();

            // Tidak melakukan insert/update ke tbl_kota_has_artefak sama sekali
        } else {
            // Validasi untuk kategori file (FTA/Dokumen)
            $request->validate([
                'file_pengumpulan' => 'required|mimes:pdf,doc,docx|max:2048',
            ], [
                'file_pengumpulan.required' => 'File pengumpulan harus diunggah.',
                'file_pengumpulan.mimes' => 'File pengumpulan harus dalam format PDF, DOC, atau DOCX.',
                'file_pengumpulan.max' => 'Ukuran file pengumpulan maksimal adalah 2MB.',
            ]);
            
            // Upload file
            $file = $request->file('file_pengumpulan');
            $originalFileName = $file->getClientOriginalName();
            $file_pengumpulan = $file->storeAs('submissions', $originalFileName, 'public');

            // Simpan/Update ke tbl_kota_has_artefak saja
            $existingSubmission = KotaHasArtefakModel::where('id_kota', $id_kota)
                ->where('id_artefak', $artefak_id)
                ->first();

            if ($existingSubmission) {
                $existingSubmission->update([
                    'file_pengumpulan' => basename($file_pengumpulan),
                    'waktu_pengumpulan' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                KotaHasArtefakModel::create([
                    'id_kota' => $id_kota,
                    'id_artefak' => $artefak_id,
                    'file_pengumpulan' => basename($file_pengumpulan),
                    'waktu_pengumpulan' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('artefak')->with('success', 'Tugas berhasil dikumpulkan!');
    }

    // Method baru untuk update teks berdasarkan artefak_id
    public function updateTeks(Request $request, $artefak_id)
    {
        // Ambil artefak untuk memastikan kategori
        $artefak = ArtefakModel::findOrFail($artefak_id);

        // Pastikan hanya kategori Teks yang bisa diupdate
        if ($artefak->kategori_artefak != 'Teks') {
            return redirect()->route('artefak')->with('error', 'Kategori artefak tidak sesuai untuk update teks.');
        }

        // Ambil user yang login
        $user = auth()->user();

        // Ambil id_kota berdasarkan user
        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');

        // Pastikan id_kota valid
        if (!$id_kota) {
            return redirect()->route('artefak')->with('error', 'Gagal memperbarui data: id_kota tidak valid.');
        }

        // Validasi input
        $request->validate([
            'teks_pengumpulan' => 'required|string|min:10',
        ], [
            'teks_pengumpulan.required' => 'Teks pengumpulan harus diisi.',
            'teks_pengumpulan.string' => 'Teks pengumpulan harus berupa teks.',
            'teks_pengumpulan.min' => 'Teks pengumpulan minimal 10 karakter.',
        ]);

        // Update kolom di tbl_kota (misalnya kolom 'abstrak')
        $kota = KotaModel::findOrFail($id_kota);
        $kota->abstrak = $request->input('teks_pengumpulan');
        $kota->updated_at = now();
        $kota->save();

        return redirect()->route('artefak')->with('success', 'Teks berhasil diperbarui!');
    }

    // Method baru untuk hapus teks berdasarkan artefak_id
    public function destroyTeks($artefak_id)
    {
        // Ambil artefak untuk memastikan kategori
        $artefak = ArtefakModel::findOrFail($artefak_id);

        // Pastikan hanya kategori Teks yang bisa dihapus
        if ($artefak->kategori_artefak != 'Teks') {
            return redirect()->route('artefak')->with('error', 'Kategori artefak tidak sesuai.');
        }

        // Ambil user yang login
        $user = auth()->user();

        // Ambil id_kota berdasarkan user
        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');

        // Pastikan id_kota valid
        if (!$id_kota) {
            return redirect()->route('artefak')->with('error', 'Gagal menghapus data: id_kota tidak valid.');
        }

        // Hapus teks dengan mengosongkan kolom abstrak
        $kota = KotaModel::findOrFail($id_kota);
        $kota->abstrak = null; // atau string kosong ''
        $kota->updated_at = now();
        $kota->save();

        return redirect()->route('artefak')->with('success', 'Teks berhasil dihapus!');
    }

    // Method asli update (untuk backward compatibility)
    public function update(Request $request, $artefak_id)
    {
        return $this->updateTeks($request, $artefak_id);
    }



    public function destroy($id)
    {
        $kumpul = KotaHasArtefakModel::findOrFail($id);
        
        // Hapus file jika ada dan tidak kosong
        if ($kumpul->file_pengumpulan && $kumpul->file_pengumpulan !== '') {
            Storage::disk('public')->delete('submissions/' . $kumpul->file_pengumpulan);
        }
        
        $kumpul->delete();

        return redirect()->route('artefak')->with('success', 'Pengumpulan berhasil dibatalkan!');
    }
}