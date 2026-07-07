<?php

namespace Database\Seeders;

use App\Models\Lookups\LuSignatoryType;
use Illuminate\Database\Seeder;

class LuSignatoryTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'staff', 'label_en' => 'Staff', 'label_fr' => 'Personnel', 'label_rw' => 'Abakozi', 'sort_order' => 1],
            ['code' => 'volunteer', 'label_en' => 'Volunteer', 'label_fr' => 'Bénévole', 'label_rw' => 'Umukorerabushake', 'sort_order' => 2],
            ['code' => 'official', 'label_en' => 'Official', 'label_fr' => 'Officiel', 'label_rw' => 'Umuyobozi', 'sort_order' => 3],
            ['code' => 'contractor', 'label_en' => 'Contractor', 'label_fr' => 'Sous-traitant', 'label_rw' => 'Uwakoranye Amasezerano', 'sort_order' => 4],
        ];

        foreach ($rows as $row) {
            LuSignatoryType::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
