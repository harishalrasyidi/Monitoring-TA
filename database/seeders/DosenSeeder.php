<?php

namespace Database\Seeders;

use App\Models\DosenModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosenFilePath = 'json_data/dosen.json';

        if (Storage::exists($dosenFilePath)) {
            $dosenJson = Storage::get($dosenFilePath);
            $dosen = json_decode($dosenJson, true);
    
            if ($dosen) {
                foreach ($dosen as $dsn) {
                    DosenModel::create([
                        'nip' => $dsn['nip'], // assuming nim is the student ID
                        'nama' => $dsn['nama'],
                        'email' => $dsn['email'],
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
    }
}
