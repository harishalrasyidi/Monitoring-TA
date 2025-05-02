<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MasterArtefakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the basic FTA artefaks
        $ftaArtefaks = [];
        for ($i = 1; $i <= 23; $i++) {
            $ftaArtefaks[] = ['nama_artefak' => 'FTA ' . str_pad($i, 2, '0', STR_PAD_LEFT)];
        }

        // Define additional specific artefaks
        $specificArtefaks = [
            ['nama_artefak' => 'FTA 05a'],
            ['nama_artefak' => 'FTA 06a'],
            ['nama_artefak' => 'FTA 09a'],
            ['nama_artefak' => 'Proposal Tugas Akhir'],
            ['nama_artefak' => 'SRS'],
            ['nama_artefak' => 'SDD'],
            ['nama_artefak' => 'Laporan Tugas Akhir']
        ];

        // Merge both arrays
        $allArtefaks = array_merge($ftaArtefaks, $specificArtefaks);

        // Insert all artefak entries into the database
        DB::table('tbl_master_artefak')->insert($allArtefaks);
    }
}
