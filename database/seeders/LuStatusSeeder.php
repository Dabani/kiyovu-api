<?php

namespace Database\Seeders;

use App\Models\Lookups\LuStatus;
use Illuminate\Database\Seeder;

class LuStatusSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'pending', 'label_en' => 'Pending', 'label_fr' => 'En attente', 'label_rw' => 'Bitegereje', 'applies_to' => 'general', 'color_hex' => '#f1c40f', 'sort_order' => 1],
            ['code' => 'active', 'label_en' => 'Active', 'label_fr' => 'Actif', 'label_rw' => 'Bikora', 'applies_to' => 'general', 'color_hex' => '#006400', 'sort_order' => 2],
            ['code' => 'inactive', 'label_en' => 'Inactive', 'label_fr' => 'Inactif', 'label_rw' => 'Ntibikora', 'applies_to' => 'general', 'color_hex' => '#95a5a6', 'sort_order' => 3],
            ['code' => 'suspended', 'label_en' => 'Suspended', 'label_fr' => 'Suspendu', 'label_rw' => 'Byahagaritswe', 'applies_to' => 'general', 'color_hex' => '#e67e22', 'sort_order' => 4],
            ['code' => 'approved', 'label_en' => 'Approved', 'label_fr' => 'Approuvé', 'label_rw' => 'Byemejwe', 'applies_to' => 'general', 'color_hex' => '#27ae60', 'sort_order' => 5],
            ['code' => 'rejected', 'label_en' => 'Rejected', 'label_fr' => 'Rejeté', 'label_rw' => 'Byanze', 'applies_to' => 'general', 'color_hex' => '#c0392b', 'sort_order' => 6],
            ['code' => 'draft', 'label_en' => 'Draft', 'label_fr' => 'Brouillon', 'label_rw' => 'Umushinga', 'applies_to' => 'general', 'color_hex' => '#7f8c8d', 'sort_order' => 7],
            ['code' => 'archived', 'label_en' => 'Archived', 'label_fr' => 'Archivé', 'label_rw' => 'Byabitswe', 'applies_to' => 'general', 'color_hex' => '#34495e', 'sort_order' => 8],
            ['code' => 'expired', 'label_en' => 'Expired', 'label_fr' => 'Expiré', 'label_rw' => 'Byarangiye', 'applies_to' => 'general', 'color_hex' => '#e74c3c', 'sort_order' => 9],
        ];

        foreach ($rows as $row) {
            LuStatus::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
