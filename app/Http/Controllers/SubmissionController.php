<?php

namespace App\Http\Controllers;

use App\Models\ArtefakModel;
use App\Models\User;
use App\Models\KotaHasArtefakModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function store(Request $request, $artefak_id)
    {
        // Ambil data artefak untuk mengetahui kategorinya
        $artefak = ArtefakModel::findOrFail($artefak_id);
        
        // Validasi berdasarkan kategori artefak
        if ($artefak->kategori_artefak == 'Teks') {
            // Validasi untuk kategori Teks (hanya teks_pengumpulan)
            $request->validate([
                'teks_pengumpulan' => 'required|string|min:10',
            ], [
                'teks_pengumpulan.required' => 'Teks pengumpulan harus diisi.',
                'teks_pengumpulan.string' => 'Teks pengumpulan harus berupa teks.',
                'teks_pengumpulan.min' => 'Teks pengumpulan minimal 10 karakter.',
            ]);
            
            $teks_pengumpulan = $request->input('teks_pengumpulan');
            $file_pengumpulan = ''; // Set empty string instead of null
            
        } else {
            // Validasi untuk kategori FTA/Dokumen (hanya file_pengumpulan)
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
            $teks_pengumpulan = ''; // Set empty string instead of null
        }

        // Mendapatkan id_kota dari user yang sedang login
        $user = auth()->user();
        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');

        // Pastikan id_kota valid sebelum menyimpan
        if ($id_kota) {
            // Cek apakah sudah ada pengumpulan sebelumnya
            $existingSubmission = KotaHasArtefakModel::where('id_kota', $id_kota)
                                                    ->where('id_artefak', $artefak_id)
                                                    ->first();
            
            if ($existingSubmission) {
                // Update pengumpulan yang sudah ada
                $dataToUpdate = [
                    'waktu_pengumpulan' => now(),
                    'updated_at' => now(),
                ];
                
                if ($artefak->kategori_artefak == 'Teks') {
                    $dataToUpdate['teks_pengumpulan'] = $teks_pengumpulan;
                    $dataToUpdate['file_pengumpulan'] = ''; // Explicitly set empty string
                } else {
                    $dataToUpdate['file_pengumpulan'] = basename($file_pengumpulan);
                    $dataToUpdate['teks_pengumpulan'] = ''; // Explicitly set empty string
                }
                
                $existingSubmission->update($dataToUpdate);
            } else {
                // Buat pengumpulan baru
                $dataToCreate = [
                    'id_kota' => $id_kota,
                    'id_artefak' => $artefak_id,
                    'waktu_pengumpulan' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                if ($artefak->kategori_artefak == 'Teks') {
                    $dataToCreate['teks_pengumpulan'] = $teks_pengumpulan;
                    $dataToCreate['file_pengumpulan'] = ''; // Set empty string instead of null
                } else {
                    $dataToCreate['file_pengumpulan'] = basename($file_pengumpulan);
                    $dataToCreate['teks_pengumpulan'] = ''; // Set empty string instead of null
                }
                
                KotaHasArtefakModel::create($dataToCreate);
            }

            return redirect()->route('artefak')->with('success', 'Tugas berhasil dikumpulkan!');
        } else {
            return redirect()->route('artefak')->with('error', 'Gagal menyimpan data: id_kota tidak valid.');
        }
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