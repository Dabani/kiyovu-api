<?php

namespace Database\Seeders;

use App\Models\Lookups\LuEmploymentType;
use Illuminate\Database\Seeder;

class LuEmploymentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'full_time', 'label_en' => 'Full-Time', 'label_fr' => 'Temps Plein', 'label_rw' => 'Igihe Cyuzuye', 'sort_order' => 1],
            ['code' => 'part_time', 'label_en' => 'Part-Time', 'label_fr' => 'Temps Partiel', 'label_rw' => 'Igihe Gito', 'sort_order' => 2],
            ['code' => 'fixed_term', 'label_en' => 'Fixed-Term Contract', 'label_fr' => 'Contrat à Durée Déterminée', 'label_rw' => 'Amasezerano y\'Igihe Kigenwe', 'sort_order' => 3],
            ['code' => 'consultancy', 'label_en' => 'Consultancy', 'label_fr' => 'Consultance', 'label_rw' => 'Ubujyanama', 'sort_order' => 4],
        ];

        foreach ($rows as $row) {
            LuEmploymentType::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
