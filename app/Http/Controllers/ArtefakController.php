<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArtefakModel;
use App\Models\KotaHasArtefakModel;
use App\Models\KotaModel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class ArtefakController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Mendapatkan data user yang sedang login
        $user = auth()->user();
        
        // Mendapatkan id_kota dari user yang sedang login
        $id_kota = DB::table('tbl_kota_has_user')
                    ->where('id_user', $user->id)
                    ->value('id_kota');


        // Ambil semua artefak
        $artefaks = ArtefakModel::all();
        
        // Untuk setiap artefak, cek apakah sudah dikumpulkan
        foreach ($artefaks as $artefak) {
            if ($artefak->kategori_artefak == 'Teks') {
                // Untuk kategori Teks, cek abstrak dari tbl_kota
                $kota = KotaModel::find($id_kota);
                if ($kota && !empty($kota->abstrak)) {
                    // Buat objek pseudo submission untuk konsistensi dengan blade template
                    $artefak->kumpul = (object) [
                        'id' => $kota->id_kota, // Menggunakan id_kota sebagai identifier
                        'teks_pengumpulan' => $kota->abstrak,
                        'file_pengumpulan' => null,
                        'waktu_pengumpulan' => $kota->updated_at ?? null
                    ];
                } else {
                    $artefak->kumpul = null;
                }
            } else {
                // Untuk kategori FTA/Dokumen, cek file_pengumpulan dari tbl_kota_has_artefak
                $submission = KotaHasArtefakModel::where('id_kota', $id_kota)
                    ->where('id_artefak', $artefak->id_artefak)
                    ->first();
                
                if ($submission) {
                    $artefak->kumpul = (object) [
                        'id' => $submission->id,
                        'teks_pengumpulan' => null,
                        'file_pengumpulan' => $submission->file_pengumpulan,
                        'waktu_pengumpulan' => $submission->waktu_pengumpulan
                    ];
                } else {
                    $artefak->kumpul = null;
                }
            }
        }

        // Ambil master artefak jika diperlukan
        $masterArtefaks = []; // Sesuaikan dengan kebutuhan Anda

        return view('artefak.index', compact('artefaks', 'masterArtefaks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_artefak' => 'required',
            'deskripsi' => 'required',
            'kategori_artefak' => 'required',
            'tanggal_tenggat' => 'required|date',
            'waktu_tenggat' => 'required|date_format:H:i',
        ]);

        $existingArtefak = DB::table('tbl_artefak')->where('nama_artefak', $request->nama_artefak)->exists();
        if($existingArtefak) {
            session()->flash('error', 'Artefak sudah terdaftar');
            return redirect()->back()->withInput();
        }

        $tanggalTenggat = $request->tanggal_tenggat;
        $waktuTenggat = $request->waktu_tenggat;
        $tenggat_waktu = $tanggalTenggat . ' ' . $waktuTenggat . ':00';

        DB::table('tbl_artefak')->insert([
            'nama_artefak' => $request->nama_artefak,
            'deskripsi' => $request->deskripsi,
            'kategori_artefak' => $request->kategori_artefak,
            'tenggat_waktu' => $tenggat_waktu,
        ]);

        // ArtefakModel::create($request->all());

        return redirect()->route('artefak')
                        ->with('success', 'Artefak berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $artefak = ArtefakModel::findOrFail($id);
        if (!$artefak) {
            return redirect()->route('artefak')->withErrors('Data tidak ditemukan.');
        }

        return view('artefak.edit', compact('artefak'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_artefak' => 'required',
            'deskripsi' => 'required',
            'kategori_artefak' => 'required',
            'tenggat_waktu' => 'required',
        ]);

        $artefak = ArtefakModel::findOrFail($id);

        $artefak->update($request->all());

        session()->flash('success', 'Data artefak berhasil dirubah');

        return redirect()->route('artefak');
    }

    public function destroy($id)
    {
        $artefak = ArtefakModel::findOrFail($id);
        Storage::delete('/artefak'. $artefak->id_artefak);
        $artefak->delete();

        session()->flash('success', 'Data artefak berhasil dihapus');

        return redirect()->route('artefak')->with('success', 'Data Artefak berhasil dihapus');
    }

}
