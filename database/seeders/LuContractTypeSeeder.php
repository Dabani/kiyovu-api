<?php

namespace Database\Seeders;

use App\Models\Lookups\LuContractType;
use Illuminate\Database\Seeder;

class LuContractTypeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'procurement', 'label_en' => 'Procurement Contract', 'label_fr' => 'Contrat d\'Approvisionnement', 'label_rw' => 'Amasezerano y\'Ibicuruzwa', 'sort_order' => 1],
            ['code' => 'partnership', 'label_en' => 'Partnership Agreement', 'label_fr' => 'Accord de Partenariat', 'label_rw' => 'Amasezerano y\'Ubufatanye', 'sort_order' => 2],
            ['code' => 'service', 'label_en' => 'Service Agreement', 'label_fr' => 'Contrat de Service', 'label_rw' => 'Amasezerano y\'Serivisi', 'sort_order' => 3],
            ['code' => 'other', 'label_en' => 'Other', 'label_fr' => 'Autre', 'label_rw' => 'Ikindi', 'sort_order' => 4],
        ];

        foreach ($rows as $row) {
            LuContractType::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
