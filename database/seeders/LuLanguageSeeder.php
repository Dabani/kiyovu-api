<?php

namespace Database\Seeders;

use App\Models\Lookups\LuLanguage;
use Illuminate\Database\Seeder;

class LuLanguageSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'en', 'label_en' => 'English', 'label_fr' => 'Anglais', 'label_rw' => 'Icyongereza', 'sort_order' => 1],
            ['code' => 'fr', 'label_en' => 'French', 'label_fr' => 'Français', 'label_rw' => 'Igifaransa', 'sort_order' => 2],
            ['code' => 'rw', 'label_en' => 'Kinyarwanda', 'label_fr' => 'Kinyarwanda', 'label_rw' => 'Ikinyarwanda', 'sort_order' => 3],
        ];

        foreach ($rows as $row) {
            LuLanguage::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
