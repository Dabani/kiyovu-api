<?php

namespace Database\Seeders;

use App\Models\Lookups\LuNoticeType;
use Illuminate\Database\Seeder;

class LuNoticeTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'allegations', 'label_en' => 'Notice of Allegations', 'label_fr' => 'Avis d\'Allégations', 'label_rw' => 'Itangazo ry\'Ibirego', 'minimum_notice_days' => 14, 'sort_order' => 1],
            ['code' => 'hearing', 'label_en' => 'Notice of Hearing', 'label_fr' => 'Avis d\'Audience', 'label_rw' => 'Itangazo ry\'Iburanisha', 'minimum_notice_days' => 14, 'sort_order' => 2],
        ];

        foreach ($rows as $row) {
            LuNoticeType::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
