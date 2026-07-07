<?php

namespace Database\Seeders;

use App\Models\Lookups\LuFanSanction;
use Illuminate\Database\Seeder;

class LuFanSanctionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'stadium_ban_temporary', 'label_en' => 'Stadium Ban — Temporary', 'label_fr' => 'Interdiction de Stade — Temporaire', 'label_rw' => 'Kubuzwa Kwinjira mu Kibuga — By\'igihe', 'sort_order' => 1],
            ['code' => 'stadium_ban_permanent', 'label_en' => 'Stadium Ban — Permanent', 'label_fr' => 'Interdiction de Stade — Permanente', 'label_rw' => 'Kubuzwa Kwinjira mu Kibuga — Burundu', 'sort_order' => 2],
            ['code' => 'recognition_withdrawn', 'label_en' => 'Withdrawal of Fan Club Recognition', 'label_fr' => 'Retrait de Reconnaissance', 'label_rw' => 'Gukuraho Kwemerwa', 'sort_order' => 3],
            ['code' => 'law_enforcement_referral', 'label_en' => 'Referral to Law Enforcement', 'label_fr' => 'Renvoi aux Forces de l\'Ordre', 'label_rw' => 'Kwoherezwa Polisi', 'sort_order' => 4],
        ];

        foreach ($rows as $row) {
            LuFanSanction::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
