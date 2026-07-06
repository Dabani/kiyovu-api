<?php

namespace Database\Seeders;

use App\Models\Lookups\LuWhistleblowerCategory;
use Illuminate\Database\Seeder;

class LuWhistleblowerCategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'financial_misconduct', 'label_en' => 'Financial Misconduct', 'label_fr' => 'Faute Financière', 'label_rw' => 'Ikosa mu Mari', 'sort_order' => 1],
            ['code' => 'corruption', 'label_en' => 'Corruption', 'label_fr' => 'Corruption', 'label_rw' => 'Ruswa', 'sort_order' => 2],
            ['code' => 'safeguarding', 'label_en' => 'Safeguarding Concern', 'label_fr' => 'Préoccupation de Protection', 'label_rw' => 'Impungenge zo Kurengera', 'sort_order' => 3],
            ['code' => 'retaliation', 'label_en' => 'Retaliation', 'label_fr' => 'Représailles', 'label_rw' => 'Guhora', 'sort_order' => 4],
            ['code' => 'other', 'label_en' => 'Other Misconduct', 'label_fr' => 'Autre Faute', 'label_rw' => 'Ikindi Kosa', 'sort_order' => 5],
        ];

        foreach ($rows as $row) {
            LuWhistleblowerCategory::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
