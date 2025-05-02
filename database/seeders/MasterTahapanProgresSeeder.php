<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MasterTahapanProgresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $progres = [
            [
                'nama_progres' => 'Seminar 1',
            ],
            [
                'nama_progres' => 'Seminar 2',
            ],
            [
                'nama_progres' => 'Seminar 3',
            ],
            [
                'nama_progres' => 'Sidang',
            ],
        ];

        DB::table('tbl_master_tahapan_progres')->insert($progres);
    }
}


