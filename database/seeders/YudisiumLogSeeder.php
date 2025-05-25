<?php

namespace Database\Seeders;

use App\Models\YudisiumModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YudisiumLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan data yudisium dan user untuk log
        $yudisiumList = DB::table('tbl_yudisium')->get();
        $koordinatorUsers = User::where('role', 1)->get(); // Koordinator TA (sesuaikan role jika berbeda)
        
        if ($koordinatorUsers->isEmpty()) {
            $this->command->info('Tidak ada user koordinator. Membuat log dengan user ID 1.');
            $koordinatorUsers = [User::find(1)]; // Fallback ke user ID 1 jika tidak ada koordinator
        }
        
        $logData = [];
        
        // Contoh jenis perubahan
        $jenisPerubahan = [
            'Perubahan Kategori',
            'Perubahan Status',
            'Penambahan Data',
            'Pembaruan Nilai'
        ];
        
        // Iterasi untuk membuat log
        foreach ($yudisiumList as $index => $yudisium) {
            // Hanya buat log untuk beberapa data saja (misalnya 50%)
            if (rand(0, 1) == 0) {
                continue;
            }
            
            // Pilih user acak dari daftar koordinator
            $user = $koordinatorUsers[array_rand($koordinatorUsers->toArray())];
            
            // Pilih jenis perubahan acak
            $jenis = $jenisPerubahan[array_rand($jenisPerubahan)];
            
            // Buat nilai lama dan baru
            $nilaiLama = null;
            $nilaiBaru = null;
            
            if ($jenis == 'Perubahan Kategori') {
                $nilaiLama = json_encode(['kategori_yudisium' => ($yudisium->kategori_yudisium == 1 ? 3 : $yudisium->kategori_yudisium - 1)]);
                $nilaiBaru = json_encode(['kategori_yudisium' => $yudisium->kategori_yudisium]);
            } elseif ($jenis == 'Perubahan Status') {
                $statusLama = $yudisium->status == 'pending' ? 'rejected' : 'pending';
                $nilaiLama = json_encode(['status' => $statusLama]);
                $nilaiBaru = json_encode(['status' => $yudisium->status]);
            } elseif ($jenis == 'Pembaruan Nilai') {
                $nilaiLama = json_encode(['nilai_akhir' => round($yudisium->nilai_akhir - 0.25, 2)]);
                $nilaiBaru = json_encode(['nilai_akhir' => $yudisium->nilai_akhir]);
            } else {
                $nilaiLama = null;
                $nilaiBaru = json_encode([
                    'id_kota' => $yudisium->id_kota,
                    'kategori_yudisium' => $yudisium->kategori_yudisium,
                    'tanggal_yudisium' => $yudisium->tanggal_yudisium,
                    'nilai_akhir' => $yudisium->nilai_akhir,
                    'status' => $yudisium->status
                ]);
            }
            
            // Tambahkan ke data log
            $logData[] = [
                'id_yudisium' => $yudisium->id_yudisium,
                'id_user' => $user->id,
                'jenis_perubahan' => $jenis,
                'nilai_lama' => $nilaiLama,
                'nilai_baru' => $nilaiBaru,
                'waktu_perubahan' => Carbon::now()->subDays(rand(1, 14))->subHours(rand(1, 23)),
                'keterangan' => "Log perubahan untuk yudisium KoTA {$yudisium->id_kota}"
            ];
            
            // Setiap 10 data, masukkan ke database dan kosongkan array
            if (count($logData) >= 10 || $index == count($yudisiumList) - 1) {
                DB::table('tbl_yudisium_log')->insert($logData);
                $logData = [];
            }
        }
    }
}