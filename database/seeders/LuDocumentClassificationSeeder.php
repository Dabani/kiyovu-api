<?php

namespace Database\Seeders;

use App\Models\Lookups\LuDocumentClassification;
use Illuminate\Database\Seeder;

class LuDocumentClassificationSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'public', 'label_en' => 'Public', 'label_fr' => 'Public', 'label_rw' => 'Rusange', 'retention_years' => null, 'sort_order' => 1],
            ['code' => 'internal', 'label_en' => 'Internal', 'label_fr' => 'Interne', 'label_rw' => 'By\'imbere', 'retention_years' => 10, 'sort_order' => 2],
            ['code' => 'confidential', 'label_en' => 'Confidential', 'label_fr' => 'Confidentiel', 'label_rw' => 'Ibanga', 'retention_years' => 10, 'sort_order' => 3],
            ['code' => 'restricted', 'label_en' => 'Restricted', 'label_fr' => 'Restreint', 'label_rw' => 'Bibujijwe', 'retention_years' => 20, 'sort_order' => 4],
        ];

        foreach ($rows as $row) {
            LuDocumentClassification::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
