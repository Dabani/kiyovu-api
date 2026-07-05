<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LuLanguageSeeder::class,
            LuStatusSeeder::class,
            LuDocumentClassificationSeeder::class,
            RoleAndPermissionSeeder::class,
            SuperAdminSeeder::class,
            // Bundle seeders (lu_membership_categories, lu_fee_tiers, etc.)
            // are appended here as each module bundle is delivered.
        ]);
    }
}
