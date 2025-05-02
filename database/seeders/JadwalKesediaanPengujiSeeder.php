<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class JadwalKesediaanPengujiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data user dengan role 2
        $users = User::where('role', 2)->get();

        // Buat array data untuk insert ke tbl_jadwal_kesediaan_penguji
        $data = [];
        $now = Carbon::createFromDate(2024, 7, 1); // Mulai dari 1 Juli 2024

        foreach ($users as $key => $user) {
            if ($key >= 5) break; // Hanya lima data

            $data[] = [
                'nama_penguji' => $user->name,
                'tanggal_mulai' => $now->copy()->addDays($key)->toDateString(),
                'tanggal_selesai' => $now->copy()->addDays($key + 2)->toDateString(),
                'status' => $key < 2 ? 0 : 1, // Dua status belum fix
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert data ke tabel tbl_jadwal_kesediaan_penguji
        DB::table('tbl_jadwal_kesediaan_penguji')->insert($data);
    }
}
