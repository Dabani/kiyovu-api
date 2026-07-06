<?php

namespace Database\Seeders;

use App\Models\Lookups\LuLegalUrgency;
use Illuminate\Database\Seeder;

class LuLegalUrgencySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'immediate', 'label_en' => 'Immediate (deadline < 14 days)', 'label_fr' => 'Immédiat', 'label_rw' => 'Byihutirwa', 'sort_order' => 1],
            ['code' => 'standard', 'label_en' => 'Standard', 'label_fr' => 'Standard', 'label_rw' => 'Bisanzwe', 'sort_order' => 2],
            ['code' => 'advisory', 'label_en' => 'Advisory', 'label_fr' => 'Consultatif', 'label_rw' => 'Inama', 'sort_order' => 3],
        ];

        foreach ($rows as $row) {
            LuLegalUrgency::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
