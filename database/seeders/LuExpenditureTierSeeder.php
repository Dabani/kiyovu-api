<?php

namespace Database\Seeders;

use App\Models\Lookups\LuExpenditureTier;
use Illuminate\Database\Seeder;

class LuExpenditureTierSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'code' => 'routine', 'label_en' => 'Routine Expenditure', 'label_fr' => 'Dépense Courante',
                'label_rw' => 'Amafaranga Asanzwe', 'min_amount_rwf' => 50001, 'max_amount_rwf' => 500000,
                'required_authoriser_en' => 'CEO', 'sort_order' => 1,
            ],
            [
                'code' => 'significant', 'label_en' => 'Significant Expenditure', 'label_fr' => 'Dépense Importante',
                'label_rw' => 'Amafaranga Menshi', 'min_amount_rwf' => 500001, 'max_amount_rwf' => 5000000,
                'required_authoriser_en' => 'CEO and Treasurer (co-signed)', 'sort_order' => 2,
            ],
            [
                'code' => 'major', 'label_en' => 'Major Expenditure', 'label_fr' => 'Dépense Majeure',
                'label_rw' => 'Amafaranga Manini', 'min_amount_rwf' => 5000001, 'max_amount_rwf' => 20000000,
                'required_authoriser_en' => 'Executive Organ resolution', 'sort_order' => 3,
            ],
            [
                'code' => 'capital', 'label_en' => 'Capital Expenditure / Long-Term Commitment', 'label_fr' => 'Dépense d\'Investissement',
                'label_rw' => 'Ishoramari Rirerire', 'min_amount_rwf' => 20000001, 'max_amount_rwf' => null,
                'required_authoriser_en' => 'General Assembly resolution', 'sort_order' => 4,
            ],
        ];

        foreach ($rows as $row) {
            LuExpenditureTier::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
