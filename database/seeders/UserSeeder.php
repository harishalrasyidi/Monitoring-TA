<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'role' => 1,
            'nomor_induk' => 198608202019031014,
            'name' => 'Trisna',
            'email' => 'trisna@polban.ac.id',
            'password' => Hash::make('1234567890'),
        ]);


        $dosenFilePath = 'json_data/dosen.json';

        if (Storage::exists($dosenFilePath)) {
            $dosenJson = Storage::get($dosenFilePath);
            $dosen = json_decode($dosenJson, true);
    
            if ($dosen) {
                foreach ($dosen as $dosen) {
                    User::create([
                        'role' => 2,
                        'nomor_induk' => $dosen['nip'], // assuming nim is the student ID
                        'name' => $dosen['nama'],
                        'email' => $dosen['email'],
                        'password' => Hash::make('1234567890'),
                    ]);
                }
            } else {
                // Handle JSON decode error if needed
                echo "Failed to decode JSON file.";
            }
        } else {
            // Handle file not found error if needed
            echo "File dosen.json not found.";
        }



        $mahasiswaFilePath = 'json_data/mahasiswa.json';

        if (Storage::exists($mahasiswaFilePath)) {
            $mahasiswaJson = Storage::get($mahasiswaFilePath);
            $mahasiswa = json_decode($mahasiswaJson, true);
    
            if ($mahasiswa) {
                foreach ($mahasiswa as $mhs) {
                    User::create([
                        'role' => 3,
                        'nomor_induk' => $mhs['nim'], // assuming nim is the student ID
                        'name' => $mhs['nama'],
                        'email' => $mhs['email'],
                        'password' => Hash::make('1234567890'),
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

        User::create([
            'role' => 4,
            'nomor_induk' => 197109031999032001,
            'name' => 'Santi Sundari',
            'email' => 'santi.sundari@polban.ac.id',
            'password' => Hash::make('1234567890'),
        ]);

        // Head of Study Program (Kaprodi D3)
        User::create([
            'role' => 5,
            'nomor_induk' => 199301062019031017,
            'name' => 'Lukmannul Hakim Firdaus',
            'email' => 'lukmannul.hakim@polban.ac.id',
            'password' => Hash::make('1234567890'),
        ]);

    }

}
