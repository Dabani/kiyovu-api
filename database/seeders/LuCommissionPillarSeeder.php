<?php

namespace Database\Seeders;

use App\Models\Lookups\LuCommissionPillar;
use Illuminate\Database\Seeder;

class LuCommissionPillarSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'sport_competitive_excellence', 'label_en' => 'Sport and Competitive Excellence', 'label_fr' => 'Sport et Excellence Compétitive', 'label_rw' => 'Imikino n\'Ubunyangamugayo', 'sort_order' => 1],
            ['code' => 'infrastructure_equipment', 'label_en' => 'Infrastructure and Equipment Development', 'label_fr' => 'Infrastructure et Équipement', 'label_rw' => 'Ibikorwa Remezo n\'Ibikoresho', 'sort_order' => 2],
            ['code' => 'academy_structure', 'label_en' => 'Academy and Structure', 'label_fr' => 'Académie et Structure', 'label_rw' => 'Ishuri n\'Imiterere', 'sort_order' => 3],
            ['code' => 'financial_marketing', 'label_en' => 'Financial and Marketing', 'label_fr' => 'Finance et Marketing', 'label_rw' => 'Imari na Marketing', 'sort_order' => 4],
            ['code' => 'mobilisation_community', 'label_en' => 'Mobilisation and Community Impact', 'label_fr' => 'Mobilisation et Impact Communautaire', 'label_rw' => 'Guteza Imbere Umuryango', 'sort_order' => 5],
            ['code' => 'professional_excellence', 'label_en' => 'Professional Excellence', 'label_fr' => 'Excellence Professionnelle', 'label_rw' => 'Ubunyamwuga', 'sort_order' => 6],
        ];

        foreach ($rows as $row) {
            LuCommissionPillar::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
