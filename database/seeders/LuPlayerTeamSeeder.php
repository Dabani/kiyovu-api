<?php

namespace Database\Seeders;

use App\Models\Lookups\LuPlayerTeam;
use Illuminate\Database\Seeder;

class LuPlayerTeamSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'senior', 'label_en' => 'Senior Team', 'label_fr' => 'Équipe Première', 'label_rw' => 'Itsinda Rikuru', 'sort_order' => 1],
            ['code' => 'reserve', 'label_en' => 'Reserve Team', 'label_fr' => 'Équipe Réserve', 'label_rw' => 'Itsinda ry\'Igihe Gito', 'sort_order' => 2],
            ['code' => 'academy', 'label_en' => 'Academy', 'label_fr' => 'Académie', 'label_rw' => 'Ishuri ry\'Imikino', 'sort_order' => 3],
        ];

        foreach ($rows as $row) {
            LuPlayerTeam::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
