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
            // Bundle 1 — Membership & Honorary
            LuMembershipCategorySeeder::class,
            LuFeeTierSeeder::class,
            LuPaymentMethodSeeder::class,
            LuNomineeTypeSeeder::class,
            // Bundle 2 — HR & Recruitment
            LuHqPositionSeeder::class,
            LuEmploymentTypeSeeder::class,
            LuGiftDispositionSeeder::class,
            // Bundle 3 — Elections
            LuElectedPositionSeeder::class,
            LuDisputeGroundSeeder::class,
            // Bundle 4 — Disciplinary & Legal
            LuDisciplinaryCaseSourceSeeder::class,
            LuDisciplinarySanctionSeeder::class,
            LuNoticeTypeSeeder::class,
            LuWhistleblowerCategorySeeder::class,
            LuLegalForumSeeder::class,
            LuLegalUrgencySeeder::class,
            // Bundle 5 — Financial, Procurement & Asset
            LuExpenditureTierSeeder::class,
            LuAssetCategorySeeder::class,
            LuContractTypeSeeder::class,
            // Further bundle seeders (lu_* tables for HR, Elections, etc.)
            // are appended here as each module bundle is delivered.
        ]);
    }
}
