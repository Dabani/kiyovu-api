<?php

namespace Database\Seeders;

use App\Models\Lookups\LuIncidentType;
use Illuminate\Database\Seeder;

class LuIncidentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'pitch_invasion', 'label_en' => 'Pitch Invasion', 'label_fr' => 'Invasion de Terrain', 'label_rw' => 'Kwinjira mu Kibuga', 'sort_order' => 1],
            ['code' => 'violence', 'label_en' => 'Acts of Violence', 'label_fr' => 'Actes de Violence', 'label_rw' => 'Ihohoterwa', 'sort_order' => 2],
            ['code' => 'prohibited_substances', 'label_en' => 'Use of Prohibited Substances', 'label_fr' => 'Substances Interdites', 'label_rw' => 'Ibiyobyabwenge Bibujijwe', 'sort_order' => 3],
            ['code' => 'discriminatory_behaviour', 'label_en' => 'Discriminatory Behaviour', 'label_fr' => 'Comportement Discriminatoire', 'label_rw' => 'Ivangura', 'sort_order' => 4],
            ['code' => 'property_destruction', 'label_en' => 'Destruction of Property', 'label_fr' => 'Destruction de Biens', 'label_rw' => 'Kwangiza Umutungo', 'sort_order' => 5],
            ['code' => 'other', 'label_en' => 'Other', 'label_fr' => 'Autre', 'label_rw' => 'Ikindi', 'sort_order' => 6],
        ];

        foreach ($rows as $row) {
            LuIncidentType::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
