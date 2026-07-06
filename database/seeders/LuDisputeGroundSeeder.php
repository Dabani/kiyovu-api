<?php

namespace Database\Seeders;

use App\Models\Lookups\LuDisputeGround;
use Illuminate\Database\Seeder;

class LuDisputeGroundSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'procedural_error', 'label_en' => 'Procedural Error', 'label_fr' => 'Erreur de Procédure', 'label_rw' => 'Ikosa mu Buryo', 'sort_order' => 1],
            ['code' => 'candidate_disqualification_discovered', 'label_en' => 'Post-Election Discovery of Disqualifying Fact', 'label_fr' => 'Découverte d\'un Fait Disqualifiant', 'label_rw' => 'Kuvumbura Impamvu yo Kwima Uburenganzira', 'sort_order' => 2],
            ['code' => 'prohibited_campaign_conduct', 'label_en' => 'Prohibited Campaign Conduct', 'label_fr' => 'Comportement de Campagne Interdit', 'label_rw' => 'Imyitwarire Ibujijwe mu Kampanye', 'sort_order' => 3],
            ['code' => 'electoral_fraud', 'label_en' => 'Electoral Fraud', 'label_fr' => 'Fraude Électorale', 'label_rw' => 'Uburiganya mu Matora', 'sort_order' => 4],
        ];

        foreach ($rows as $row) {
            LuDisputeGround::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
