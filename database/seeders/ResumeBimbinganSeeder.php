<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResumeBimbinganModel;
use Carbon\Carbon;

class ResumeBimbinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResumeBimbinganModel::create([
            'tanggal_bimbingan' => Carbon::create('2024', '05', '15'),
            'jam_mulai' => '10:30:00',
            'jam_selesai' => '11:30:00',
            'isi_resume_bimbingan' => 'Diskusi awal mengenai topik tugas akhir.',
            'isi_revisi_bimbingan' => '-', // Tambahkan nilai default di sini
            'tahapan_progres' => 2,
            'sesi_bimbingan' => 1,
        ]);

        ResumeBimbinganModel::create([
            'tanggal_bimbingan' => Carbon::create('2024', '05', '15'),
            'jam_mulai' => '10:30:00',
            'jam_selesai' => '11:30:00',
            'isi_resume_bimbingan' => 'Diskusi awal mengenai topik tugas akhir.',
            'isi_revisi_bimbingan' => '-', // Tambahkan nilai default di sini
            'tahapan_progres' => 2,
            'sesi_bimbingan' => 2,
        ]);

        ResumeBimbinganModel::create([
            'tanggal_bimbingan' => Carbon::create('2024', '06', '05'),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '10:00:00',
            'isi_resume_bimbingan' => 'Revisi proposal dan persiapan seminar.',
            'isi_revisi_bimbingan' => '-', // Tambahkan nilai default di sini
            'tahapan_progres' => 2,
            'sesi_bimbingan' => 3,
        ]);
    }
}
