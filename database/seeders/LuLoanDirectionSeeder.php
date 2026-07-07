<?php

namespace Database\Seeders;

use App\Models\Lookups\LuLoanDirection;
use Illuminate\Database\Seeder;

class LuLoanDirectionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'outgoing', 'label_en' => 'Outgoing Loan', 'label_fr' => 'Prêt Sortant', 'label_rw' => 'Ubugururwa Busohoka', 'sort_order' => 1],
            ['code' => 'incoming', 'label_en' => 'Incoming Loan', 'label_fr' => 'Prêt Entrant', 'label_rw' => 'Ubugururwa Bwinjira', 'sort_order' => 2],
        ];

        foreach ($rows as $row) {
            LuLoanDirection::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
