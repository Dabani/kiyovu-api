<?php

namespace Database\Seeders;

use App\Models\Lookups\LuLegalForum;
use Illuminate\Database\Seeder;

class LuLegalForumSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'ferwafa', 'label_en' => 'FERWAFA', 'label_fr' => 'FERWAFA', 'label_rw' => 'FERWAFA', 'sort_order' => 1],
            ['code' => 'caf', 'label_en' => 'CAF', 'label_fr' => 'CAF', 'label_rw' => 'CAF', 'sort_order' => 2],
            ['code' => 'fifa', 'label_en' => 'FIFA', 'label_fr' => 'FIFA', 'label_rw' => 'FIFA', 'sort_order' => 3],
            ['code' => 'cas_tas', 'label_en' => 'CAS / TAS', 'label_fr' => 'CAS / TAS', 'label_rw' => 'CAS / TAS', 'sort_order' => 4],
            ['code' => 'rwandan_courts', 'label_en' => 'Rwandan Courts', 'label_fr' => 'Tribunaux Rwandais', 'label_rw' => 'Inkiko z\'u Rwanda', 'sort_order' => 5],
            ['code' => 'other', 'label_en' => 'Other', 'label_fr' => 'Autre', 'label_rw' => 'Ikindi', 'sort_order' => 6],
        ];

        foreach ($rows as $row) {
            LuLegalForum::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
