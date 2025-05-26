<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TblKotaHasArtefakSeeder extends Seeder
{
    public function run()
    {
        DB::table('tbl_kota_has_artefak')->insert([
            [
                'id_kota' => 1,
                'id_artefak' => 1,
                'file_pengumpulan' => 'files/artefak1_kota1.pdf',
                'waktu_pengumpulan' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id_kota' => 1,
                'id_artefak' => 2,
                'file_pengumpulan' => 'files/artefak2_kota1.pdf',
                'waktu_pengumpulan' => Carbon::now()->subDay(),
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ],
            [
                'id_kota' => 2,
                'id_artefak' => 1,
                'file_pengumpulan' => 'files/artefak1_kota2.pdf',
                'waktu_pengumpulan' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 