<?php

namespace Database\Seeders;

use App\Models\Lookups\LuLanguage;
use App\Models\Lookups\LuStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $active = LuStatus::where('code', 'active')->first();
        $en = LuLanguage::where('code', 'en')->first();

        $admin = User::updateOrCreate(
            ['email' => 'admin@kiyovusports.net'],
            [
                'name' => 'System Administrator',
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'password' => Hash::make(env('SUPER_ADMIN_PASSWORD', 'Victory@1964')),
                'status_id' => $active?->id,
                'preferred_language_id' => $en?->id,
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
    }
}
