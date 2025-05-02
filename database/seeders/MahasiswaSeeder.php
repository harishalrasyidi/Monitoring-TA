<?php

namespace Database\Seeders;

use App\Models\MahasiswaModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswaFilePath = 'json_data/mahasiswa.json';

        if (Storage::exists($mahasiswaFilePath)) {
            $mahasiswaJson = Storage::get($mahasiswaFilePath);
            $mahasiswa = json_decode($mahasiswaJson, true);
    
            if ($mahasiswa) {
                foreach ($mahasiswa as $mhs) {
                    MahasiswaModel::create([
                        'nim' => $mhs['nim'], // assuming nim is the student ID
                        'nama' => $mhs['nama'],
                        'email' => $mhs['email'],
                    ]);
                }
            } else {
                // Handle JSON decode error if needed
                echo "Failed to decode JSON file.";
            }
        } else {
            // Handle file not found error if needed
            echo "File mahasiswa.json not found.";
        }
    }
}
