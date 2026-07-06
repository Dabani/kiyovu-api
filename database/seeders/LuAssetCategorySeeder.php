<?php

namespace Database\Seeders;

use App\Models\Lookups\LuAssetCategory;
use Illuminate\Database\Seeder;

class LuAssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'equipment', 'label_en' => 'Sports Equipment', 'label_fr' => 'Équipement Sportif', 'label_rw' => 'Ibikoresho by\'Imikino', 'sort_order' => 1],
            ['code' => 'vehicle', 'label_en' => 'Vehicle', 'label_fr' => 'Véhicule', 'label_rw' => 'Imodoka', 'sort_order' => 2],
            ['code' => 'real_estate', 'label_en' => 'Real Estate / Facility', 'label_fr' => 'Immobilier', 'label_rw' => 'Umutungo Utimukanwa', 'sort_order' => 3],
            ['code' => 'it', 'label_en' => 'IT Equipment', 'label_fr' => 'Équipement Informatique', 'label_rw' => 'Ibikoresho bya IT', 'sort_order' => 4],
            ['code' => 'furniture', 'label_en' => 'Furniture & Fixtures', 'label_fr' => 'Mobilier', 'label_rw' => 'Ibikoresho by\'Ibiro', 'sort_order' => 5],
            ['code' => 'other', 'label_en' => 'Other', 'label_fr' => 'Autre', 'label_rw' => 'Ikindi', 'sort_order' => 6],
        ];

        foreach ($rows as $row) {
            LuAssetCategory::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
