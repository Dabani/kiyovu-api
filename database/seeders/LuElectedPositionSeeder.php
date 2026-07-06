<?php

namespace Database\Seeders;

use App\Models\Lookups\LuElectedPosition;
use Illuminate\Database\Seeder;

class LuElectedPositionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'president', 'label_en' => 'President', 'label_fr' => 'Président', 'label_rw' => 'Perezida', 'term_years' => 3, 'requires_criminal_record_certificate' => true, 'sort_order' => 1],
            ['code' => 'vice_president', 'label_en' => 'Vice President', 'label_fr' => 'Vice-Président', 'label_rw' => 'Visi Perezida', 'term_years' => 3, 'requires_criminal_record_certificate' => true, 'sort_order' => 2],
            ['code' => 'secretary_general', 'label_en' => 'Secretary General', 'label_fr' => 'Secrétaire Général', 'label_rw' => 'Umunyamabanga Mukuru', 'term_years' => 3, 'requires_criminal_record_certificate' => false, 'sort_order' => 3],
            ['code' => 'treasurer', 'label_en' => 'Treasurer', 'label_fr' => 'Trésorier', 'label_rw' => 'Umubitsi', 'term_years' => 3, 'requires_criminal_record_certificate' => false, 'sort_order' => 4],
            ['code' => 'director_technical_affairs', 'label_en' => 'Director of Technical Affairs', 'label_fr' => 'Directeur des Affaires Techniques', 'label_rw' => 'Umuyobozi w\'Ibikorwa Bya Tekiniki', 'term_years' => 3, 'requires_criminal_record_certificate' => false, 'sort_order' => 5],
            ['code' => 'board_director', 'label_en' => 'Board Director', 'label_fr' => 'Administrateur du Conseil', 'label_rw' => 'Umujyanama w\'Inama y\'Ubutegetsi', 'term_years' => 3, 'requires_criminal_record_certificate' => false, 'sort_order' => 6],
            ['code' => 'audit_organ_member', 'label_en' => 'Audit Organ Member', 'label_fr' => 'Membre de l\'Organe d\'Audit', 'label_rw' => 'Umunyamuryango w\'Igenzura', 'term_years' => 3, 'requires_criminal_record_certificate' => false, 'sort_order' => 7],
        ];

        foreach ($rows as $row) {
            LuElectedPosition::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
