<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(3)->create();
        $this->call([
            DosenSeeder::class,
            MahasiswaSeeder::class,
            UserSeeder::class,
            MasterTahapanProgresSeeder::class,
            KotaSeeder::class,
            MasterArtefakSeeder::class,
            ArtefakSeeder::class,
            ResumeBimbinganSeeder::class,
            KotaHasUserSeeder::class,
            // JadwalKegiatanSeeder::class,
            TimelineSeeder::class,
            TimelineHasArtefakSeeder::class,
            MasterMetodologiSeeder::class,
            JadwalKesediaanPengujiSeeder::class,

        ]);
    }
}
