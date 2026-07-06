<?php

namespace Database\Seeders;

use App\Models\Lookups\LuMembershipCategory;
use Illuminate\Database\Seeder;

class LuMembershipCategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'code' => 'founder', 'label_en' => 'Founder Member', 'label_fr' => 'Membre Fondateur',
                'label_rw' => 'Umunyamuryango Washinze', 'sort_order' => 1,
                'description_en' => 'Original founding members of Kiyovu Sports Association.',
            ],
            [
                'code' => 'adherent', 'label_en' => 'Adherent Member', 'label_fr' => 'Membre Adhérent',
                'label_rw' => 'Umunyamuryango Wisunze', 'sort_order' => 2,
                'description_en' => 'Members admitted after founding, via written application (MEM-001).',
            ],
        ];

        foreach ($rows as $row) {
            LuMembershipCategory::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
