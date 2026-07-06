<?php

namespace Database\Seeders;

use App\Models\Lookups\LuGiftDisposition;
use Illuminate\Database\Seeder;

class LuGiftDispositionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'organisation_property', 'label_en' => 'Becomes Organisation Property', 'label_fr' => 'Devient Propriété de l\'Organisation', 'label_rw' => 'Biba iby\'Umuryango', 'sort_order' => 1],
            ['code' => 'returned_to_donor', 'label_en' => 'Returned to Donor', 'label_fr' => 'Retourné au Donateur', 'label_rw' => 'Byasubijwe Uwatanze', 'sort_order' => 2],
            ['code' => 'retained_approved', 'label_en' => 'Retained by Declarant (Executive Organ approved)', 'label_fr' => 'Conservé par le Déclarant (approuvé)', 'label_rw' => 'Byemerewe Kuguma ku Wabitangaje', 'sort_order' => 3],
        ];

        foreach ($rows as $row) {
            LuGiftDisposition::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
