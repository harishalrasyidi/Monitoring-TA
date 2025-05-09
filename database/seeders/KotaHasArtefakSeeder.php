<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KotaHasArtefakModel;
use App\Models\KotaModel;
use App\Models\ArtefakModel;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KotaHasArtefakSeeder extends Seeder
{
    public function run()
    {
        $kotaList = KotaModel::all();
        $artefakList = ArtefakModel::all();

        foreach ($kotaList as $kota) {
            foreach ($artefakList as $artefak) {
                KotaHasArtefakModel::create([
                    'id_kota' => $kota->id_kota,
                    'id_artefak' => $artefak->id_artefak,
                    'file_pengumpulan' => Str::slug($kota->nama_kota . '_' . $artefak->nama_artefak) . '.pdf',
                    'waktu_pengumpulan' => Carbon::create(rand(2020, 2025), rand(1, 12), rand(1, 28)),
                    'kategori' => $artefak->kategori_artefak,
                    'prodi' => substr($kota->kelas, 0, 2), // asumsi kelas awalnya adalah kode prodi, misal "TI2022A"
                ]);
            }
        }
    }
}
