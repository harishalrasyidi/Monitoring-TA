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
        $artefak = ArtefakModel::findOrFail($artefak_id);

        if (strtolower($artefak->nama_artefak) === 'poster') {
            $rules = ['file_pengumpulan' => 'required|mimes:jpg,jpeg,png|max:2048'];
            $messages = [
                'file_pengumpulan.required' => 'File pengumpulan harus diunggah.',
                'file_pengumpulan.mimes' => 'File pengumpulan harus dalam format JPG atau PNG.',
                'file_pengumpulan.max' => 'Ukuran file pengumpulan maksimal adalah 2MB.',
            ];
        } else {
            $rules = ['file_pengumpulan' => 'required|mimes:pdf|max:2048'];
            $messages = [
                'file_pengumpulan.required' => 'File pengumpulan harus diunggah.',
                'file_pengumpulan.mimes' => 'File pengumpulan harus dalam format PDF.',
                'file_pengumpulan.max' => 'Ukuran file pengumpulan maksimal adalah 2MB.',
            ];
        }

        $request->validate($rules, $messages);

        $file = $request->file('file_pengumpulan');
        $originalFileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('submissions', $originalFileName, 'public');

        $user = auth()->user();
        $id_kota = DB::table('tbl_kota_has_user')->where('id_user', $user->id)->value('id_kota');

        if ($id_kota) {
            KotaHasArtefakModel::create([
                'id_kota' => $id_kota,
                'id_artefak' => $artefak_id,
                'file_pengumpulan' => $filePath,
                'waktu_pengumpulan' => now(),
            ]);

            return redirect()->route('artefak')->with('success', 'Tugas berhasil dikumpulkan!');
        } else {
            return redirect()->route('artefak')->with('error', 'Gagal menyimpan data: id_kota tidak valid.');
        }
    }


    public function destroy($id)
    {
        $kumpul = KotaHasArtefakModel::findOrFail($id);
        Storage::disk('public')->delete($kumpul->file_pengumpulan);
        $kumpul->delete();

        return redirect()->route('artefak')->with('success', 'Pengumpulan berhasil dibatalkan!');
    }

}
