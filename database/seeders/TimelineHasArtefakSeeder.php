<?php

namespace Database\Seeders;

use App\Models\TimelineModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TimelineHasArtefakModel;
use App\Models\MasterArterfakModel;

class TimelineHasArtefakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan Timeline dan MasterArtefak sudah diisi terlebih dahulu
        $timelines = TimelineModel::all();
        $artefaks = MasterArterfakModel::all();

        $data = [
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_master_artefak' => $artefaks->get(0)->id,
            ],
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_master_artefak' => $artefaks->get(1)->id,
            ],
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_master_artefak' => $artefaks->get(2)->id,
            ],
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_master_artefak' => $artefaks->get(3)->id,
            ],
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_master_artefak' => $artefaks->get(4)->id,
            ],
            [
                'id_timeline' => $timelines->get(0)->id_timeline,
                'id_master_artefak' => $artefaks->get(23)->id,
            ],
            [
                'id_timeline' => $timelines->get(1)->id_timeline,
                'id_master_artefak' => $artefaks->get(5)->id,
            ],
            [
                'id_timeline' => $timelines->get(1)->id_timeline,
                'id_master_artefak' => $artefaks->get(6)->id,
            ],
            [
                'id_timeline' => $timelines->get(1)->id_timeline,
                'id_master_artefak' => $artefaks->get(7)->id,
            ],
            [
                'id_timeline' => $timelines->get(1)->id_timeline,
                'id_master_artefak' => $artefaks->get(8)->id,
            ],
            [
                'id_timeline' => $timelines->get(1)->id_timeline,
                'id_master_artefak' => $artefaks->get(25)->id,
            ],
            // Tambahkan lebih banyak data jika diperlukan
        ];

        foreach ($data as $entry) {
            TimelineHasArtefakModel::create($entry);
        }
    }
}
