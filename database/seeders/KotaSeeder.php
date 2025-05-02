<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KotaModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $judulList = [
            'PENGEMBANGAN APLIKASI MONITORING TUGAS AKHIR DI JURUSAN TEKNIK KOMPUTER DAN INFORMATIKA',
            'PEMANFAATAN TELEMEDICINE LAYANAN INFORMASI INTERAKTIF BERBASIS CHATBOT DAN LAYANAN RESERVASI ANTRIAN PASIEN',
            'PENGEMBANGAN APLIKASI AUDIT MUTU INTERNAL BERBASIS WEBSITE SPMI POLBAN',
            'PENGEMBANGAN APLIKASI PENGUKURAN CAPAIAN PEMBELAJARAN BERBASIS WEB DENGAN MENGGUNAKAN METODE GRAPH',
            'RANCANG BANGUN SISTEM ANALISIS KOMPUTASI SCORE INHEREN PADA IDENTIFIKASI RESIKO DI SPI POLBAN',
            'PENGEMBANGAN APLIKASI WEB UNTUK REKOMENDASI PEMBELAJARAN ONLINE MENGGUNAKAN METODE HYBRID FILTERING',
            'PENGEMBANGAN SISTEM INFORMASI MANAJEMEN LABORATORIUM',
            'PENGEMBANGAN SISTEM E-COMMERCE UNTUK PENJUALAN PRODUK LOKAL',
            'RANCANG BANGUN APLIKASI PEMANTAUAN CUACA REALTIME',
            'PENGEMBANGAN SISTEM INFORMASI AKADEMIK BERBASIS WEB'
        ];

        $classes = [
            '1' => 101, //Angka 1 mewakili kelas D3-A
            '2' => 201, //Angka 2 mewakili kelas D3-B
            '3' => 301, //Angka 3 mewakili kelas D4-A
            '4' => 401, //Angka 4 mewakili kelas D4-B
        ];

        foreach ($classes as $class => $startId) {
            for ($i = 0; $i < 10; $i++) {
                $kota = KotaModel::create([
                    'nama_kota' => $startId + $i,
                    'judul' => $judulList[$i % count($judulList)],
                    'kelas' => $class,
                    'periode' => 2024,
                ]);

                // Pastikan kota sudah dibuat
                if ($kota) {
                    // Cari kota berdasarkan id_kota yang baru dibuat
                    $existingKota = KotaModel::find($kota->id_kota);

                    // Jika kota ada, masukkan ke tbl_kota_has_tahapan_progres
                    if ($existingKota) {
                        DB::table('tbl_kota_has_tahapan_progres')->insert([
                            'id_kota' => $existingKota->id_kota,
                            'id_master_tahapan_progres' => 1, // id_master_tahapan_progres dari tbl_master_tahapan_progres dengan id = 1
                            'status' => 'on_progres',
                        ]);
                        DB::table('tbl_kota_has_tahapan_progres')->insert([
                            'id_kota' => $existingKota->id_kota,
                            'id_master_tahapan_progres' => 2, // id_master_tahapan_progres dari tbl_master_tahapan_progres dengan id = 1
                            'status' => 'belum-disetujui',
                        ]);
                        DB::table('tbl_kota_has_tahapan_progres')->insert([
                            'id_kota' => $existingKota->id_kota,
                            'id_master_tahapan_progres' => 3, // id_master_tahapan_progres dari tbl_master_tahapan_progres dengan id = 1
                            'status' => 'belum-disetujui',
                        ]);
                        DB::table('tbl_kota_has_tahapan_progres')->insert([
                            'id_kota' => $existingKota->id_kota,
                            'id_master_tahapan_progres' => 4, // id_master_tahapan_progres dari tbl_master_tahapan_progres dengan id = 1
                            'status' => 'belum-disetujui',
                        ]);
                    } else {
                        // Handle jika kota tidak ditemukan
                        echo "Kota dengan id {$kota->id_kota} tidak ditemukan.";
                    }
                } else {
                    // Handle jika kota gagal dibuat
                    echo "Gagal membuat kota.";
                }
            }
        }
        
    }
}
