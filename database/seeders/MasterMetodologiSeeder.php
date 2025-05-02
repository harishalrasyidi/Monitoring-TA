<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MasterMetodologiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nama = [
            [
                'nama_metodologi' => 'Waterfall',
            ],
            [
                'nama_metodologi' => 'RUP',
            ],
            [
                'nama_metodologi' => 'Prototype',
            ],
            [
                'nama_metodologi' => 'Increment',
            ],
        ];

        DB::table('tbl_master_metodologi')->insert($nama);
    }
}


