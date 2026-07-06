<?php

namespace Database\Seeders;

use App\Models\Lookups\LuHqPosition;
use Illuminate\Database\Seeder;

class LuHqPositionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'ceo', 'label_en' => 'CEO / Managing Director', 'label_fr' => 'DG / Directeur Général', 'label_rw' => 'Umuyobozi Mukuru', 'division' => 'executive', 'involves_minors' => false, 'sort_order' => 1],
            ['code' => 'club_secretary', 'label_en' => 'Club Secretary (Secretariat Head)', 'label_fr' => 'Secrétaire du Club', 'label_rw' => 'Umunyamabanga w\'Ikipe', 'division' => 'business', 'involves_minors' => false, 'sort_order' => 2],
            ['code' => 'sporting_director', 'label_en' => 'Sporting Director', 'label_fr' => 'Directeur Sportif', 'label_rw' => 'Umuyobozi w\'Imikino', 'division' => 'sport', 'involves_minors' => true, 'sort_order' => 3],
            ['code' => 'head_coach', 'label_en' => 'Head Coach', 'label_fr' => 'Entraîneur Principal', 'label_rw' => 'Umutoza Mukuru', 'division' => 'sport', 'involves_minors' => true, 'sort_order' => 4],
            ['code' => 'financial_director', 'label_en' => 'Financial Director', 'label_fr' => 'Directeur Financier', 'label_rw' => 'Umuyobozi w\'Imari', 'division' => 'business', 'involves_minors' => false, 'sort_order' => 5],
            ['code' => 'marketing_director', 'label_en' => 'Marketing & Commercial Director', 'label_fr' => 'Directeur Marketing & Commercial', 'label_rw' => 'Umuyobozi wa Marketing', 'division' => 'business', 'involves_minors' => false, 'sort_order' => 6],
            ['code' => 'operations_director', 'label_en' => 'Operations Director', 'label_fr' => 'Directeur des Opérations', 'label_rw' => 'Umuyobozi w\'Ibikorwa', 'division' => 'business', 'involves_minors' => false, 'sort_order' => 7],
            ['code' => 'strategy_director', 'label_en' => 'Strategy & Development Director', 'label_fr' => 'Directeur Stratégie & Développement', 'label_rw' => 'Umuyobozi w\'Ingamba', 'division' => 'business', 'involves_minors' => false, 'sort_order' => 8],
            ['code' => 'store_manager', 'label_en' => 'Kiyovu Store Manager', 'label_fr' => 'Gérant de la Boutique Kiyovu', 'label_rw' => 'Umuyobozi w\'Iduka', 'division' => 'business', 'involves_minors' => false, 'sort_order' => 9],
            ['code' => 'slo', 'label_en' => 'Supporter Liaison Officer (SLO)', 'label_fr' => 'Agent de Liaison des Supporters', 'label_rw' => 'Umuhuza w\'Abaharanira', 'division' => 'business', 'involves_minors' => false, 'sort_order' => 10],
            ['code' => 'safety_security_officer', 'label_en' => 'Safety & Security Officer', 'label_fr' => 'Agent de Sécurité', 'label_rw' => 'Umukozi w\'Umutekano', 'division' => 'business', 'involves_minors' => false, 'sort_order' => 11],
            ['code' => 'medical_doctor', 'label_en' => 'Medical Doctor', 'label_fr' => 'Médecin', 'label_rw' => 'Muganga', 'division' => 'sport', 'involves_minors' => true, 'sort_order' => 12],
            ['code' => 'physiotherapist', 'label_en' => 'Physiotherapist', 'label_fr' => 'Kinésithérapeute', 'label_rw' => 'Umuforomo w\'Imikorere', 'division' => 'sport', 'involves_minors' => true, 'sort_order' => 13],
            ['code' => 'media_digital_officer', 'label_en' => 'Media & Digital Officer', 'label_fr' => 'Agent Médias & Numérique', 'label_rw' => 'Umukozi wa Media', 'division' => 'business', 'involves_minors' => false, 'sort_order' => 14],
            ['code' => 'child_safeguarding_officer', 'label_en' => 'Child Safeguarding Officer', 'label_fr' => 'Agent de Protection de l\'Enfance', 'label_rw' => 'Umukozi Urengera Abana', 'division' => 'sport', 'involves_minors' => true, 'sort_order' => 15],
        ];

        foreach ($rows as $row) {
            LuHqPosition::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
