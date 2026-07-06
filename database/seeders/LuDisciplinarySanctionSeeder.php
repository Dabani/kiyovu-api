<?php

namespace Database\Seeders;

use App\Models\Lookups\LuDisciplinarySanction;
use Illuminate\Database\Seeder;

class LuDisciplinarySanctionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'warning', 'label_en' => 'Warning', 'label_fr' => 'Avertissement', 'label_rw' => 'Iburira', 'sort_order' => 1],
            ['code' => 'reprimand', 'label_en' => 'Reprimand', 'label_fr' => 'Réprimande', 'label_rw' => 'Igihano cyo Kunenga', 'sort_order' => 2],
            ['code' => 'suspension', 'label_en' => 'Suspension', 'label_fr' => 'Suspension', 'label_rw' => 'Guhagarikwa', 'sort_order' => 3],
            ['code' => 'fine', 'label_en' => 'Fine', 'label_fr' => 'Amende', 'label_rw' => 'Ihazabu', 'sort_order' => 4],
            ['code' => 'termination', 'label_en' => 'Termination of Role/Employment', 'label_fr' => 'Résiliation', 'label_rw' => 'Gukurwaho ku Murimo', 'sort_order' => 5],
            ['code' => 'expulsion', 'label_en' => 'Expulsion from Membership', 'label_fr' => 'Exclusion', 'label_rw' => 'Kwirukanwa mu Munyamuryango', 'sort_order' => 6],
        ];

        foreach ($rows as $row) {
            LuDisciplinarySanction::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
