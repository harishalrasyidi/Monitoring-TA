<?php

namespace Database\Seeders;

use App\Models\YudisiumModel;
use App\Models\KotaModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YudisiumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan semua data KoTA yang ada
        $kotas = KotaModel::all();
        
        // Membuat array untuk menyimpan data yudisium
        $yudisiumData = [];
        
        // Tanggal yudisium untuk data awal
        $tanggalYudisium = Carbon::now()->addDays(30)->toDateString();
        
        // Kategori yudisium yang akan diacak
        $kategoriYudisium = [1, 2, 3];
        
        // Status yudisium yang akan diacak
        $statusYudisium = ['pending', 'approved', 'rejected'];
        
        // Iterasi melalui setiap KoTA dan buat data yudisium
        foreach ($kotas as $index => $kota) {
            // Mengacak kategori dan status
            $kategori = $kategoriYudisium[array_rand($kategoriYudisium)];
            $status = $statusYudisium[array_rand($statusYudisium)];
            
            // Nilai akhir acak antara 2.50 dan 4.00
            $nilaiAkhir = round(mt_rand(250, 400) / 100, 2);
            
            // Menambahkan data ke array
            $yudisiumData[] = [
                'id_kota' => $kota->id_kota,
                'kategori_yudisium' => $kategori,
                'tanggal_yudisium' => $tanggalYudisium,
                'nilai_akhir' => $nilaiAkhir,
                'status' => $status,
                'keterangan' => "Yudisium untuk KoTA {$kota->nama_kota}",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            
            // Setiap 10 data, masukkan ke database dan kosongkan array
            if (count($yudisiumData) >= 10 || $index == count($kotas) - 1) {
                DB::table('tbl_yudisium')->insert($yudisiumData);
                $yudisiumData = [];
            }
        }
        
        // Update status_yudisium di tabel kota
        foreach (KotaModel::all() as $kota) {
            $yudisiumStatus = DB::table('tbl_yudisium')
                               ->where('id_kota', $kota->id_kota)
                               ->value('status');
            
            if ($yudisiumStatus) {
                DB::table('tbl_kota')
                  ->where('id_kota', $kota->id_kota)
                  ->update(['status_yudisium' => $yudisiumStatus]);
            }
        }
    }
}