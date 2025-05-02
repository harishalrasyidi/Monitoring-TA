<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class KotaHasUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Array untuk menyimpan data insert
        $data = [];

        // Mengisi id_kota dari 1 sampai 40
        for ($id_kota = 1; $id_kota <= 40; $id_kota++) {
            // Mengisi id_user dengan role 2 (dosen) dari 2 sampai 44, maksimal 2 per kota
            for ($i = 0; $i < 2; $i++) {
                $id_user = 2 + ($id_kota - 1) * 2 + $i;
                if ($id_user > 44) {
                    break; // Jika id_user melebihi 44, berhenti menambah
                }
                $data[] = [
                    'id_kota' => $id_kota,
                    'id_user' => $id_user,
                ];
            }

            // Mengisi id_user dengan role 3 (mahasiswa) dari 45 sampai 108, maksimal 3 per kota
            for ($i = 0; $i < 3; $i++) {
                $id_user = 45 + ($id_kota - 1) * 3 + $i;
                if ($id_user > 132) {
                    break; // Jika id_user melebihi kondisi, berhenti menambah
                }
                $data[] = [
                    'id_kota' => $id_kota,
                    'id_user' => $id_user,
                ];
            }
        }

        // Insert data ke tabel tbl_kota_has_user
        DB::table('tbl_kota_has_user')->insert($data);
    }
}
