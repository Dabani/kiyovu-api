<?php

namespace Database\Seeders;

use App\Models\Lookups\LuNomineeType;
use Illuminate\Database\Seeder;

class LuNomineeTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'person', 'label_en' => 'Individual', 'label_fr' => 'Individu', 'label_rw' => 'Umuntu ku giti cye', 'sort_order' => 1],
            ['code' => 'organization', 'label_en' => 'Organization', 'label_fr' => 'Organisation', 'label_rw' => 'Umuryango', 'sort_order' => 2],
            ['code' => 'entity', 'label_en' => 'Other Entity', 'label_fr' => 'Autre Entité', 'label_rw' => 'Ikindi kigo', 'sort_order' => 3],
        ];

        foreach ($rows as $row) {
            LuNomineeType::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
