<?php

namespace Database\Seeders;

use App\Models\Lookups\LuFeeTier;
use Illuminate\Database\Seeder;

class LuFeeTierSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'code' => 'tier_1', 'sort_order' => 1,
                'label_en' => 'Tier 1: INZIRA (The Pathway)', 'label_fr' => 'Palier 1 : INZIRA (Le Chemin)',
                'label_rw' => 'Icyiciro 1: INZIRA', 'min_monthly_rwf' => 20000, 'max_monthly_rwf' => 50000,
                'amenities_en' => 'Full voting rights, member ID card, 15% match ticket discount, official communications.',
            ],
            [
                'code' => 'tier_2', 'sort_order' => 2,
                'label_en' => 'Tier 2: IREMEZO (The Foundation)', 'label_fr' => 'Palier 2 : IREMEZO (La Fondation)',
                'label_rw' => 'Icyiciro 2: IREMEZO', 'min_monthly_rwf' => 50001, 'max_monthly_rwf' => 100000,
                'amenities_en' => 'All Tier 1 + 25% ticket discount, priority GA seating, eligible for Commission election.',
            ],
            [
                'code' => 'tier_3', 'sort_order' => 3,
                'label_en' => 'Tier 3: INKINGI (The Pillar)', 'label_fr' => 'Palier 3 : INKINGI (Le Pilier)',
                'label_rw' => 'Icyiciro 3: INKINGI', 'min_monthly_rwf' => 100001, 'max_monthly_rwf' => 200000,
                'amenities_en' => 'All Tier 2 + 2 complimentary tickets/match, Member Lounge access, eligible for Executive Organ election.',
            ],
            [
                'code' => 'tier_4', 'sort_order' => 4,
                'label_en' => 'Tier 4: ISHINGIRO (The Builder)', 'label_fr' => 'Palier 4 : ISHINGIRO (Le Bâtisseur)',
                'label_rw' => 'Icyiciro 4: ISHINGIRO', 'min_monthly_rwf' => 200001, 'max_monthly_rwf' => 500000,
                'amenities_en' => 'All Tier 3 + 4 VIP tickets/match, VIP parking, annual luncheon with Executive Organ.',
            ],
            [
                'code' => 'tier_5', 'sort_order' => 5,
                'label_en' => 'Tier 5: INTWARI (The Champion)', 'label_fr' => 'Palier 5 : INTWARI (Le Champion)',
                'label_rw' => 'Icyiciro 5: INTWARI', 'min_monthly_rwf' => 500001, 'max_monthly_rwf' => 1000000,
                'amenities_en' => 'All Tier 4 + season VIP seat, dedicated relationship manager, quarterly CEO briefing.',
            ],
            [
                'code' => 'tier_6', 'sort_order' => 6,
                'label_en' => 'Tier 6: UMUTWARE (The Leader)', 'label_fr' => 'Palier 6 : UMUTWARE (Le Chef)',
                'label_rw' => 'Icyiciro 6: UMUTWARE', 'min_monthly_rwf' => 1000001, 'max_monthly_rwf' => 2000000,
                'amenities_en' => 'All Tier 5 + 2 season VIP seats, Board strategy session (observer), Honorary Membership eligibility after 2 years.',
            ],
        ];

        foreach ($rows as $row) {
            LuFeeTier::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
