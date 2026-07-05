<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /** Role code => human label, straight from the IRR articles cited in the blueprint. */
    private const ROLES = [
        'super_admin' => 'Super Administrator',
        'president' => 'President',
        'vice_president' => 'Vice President',
        'secretary_general' => 'Secretary General',
        'treasurer' => 'Treasurer',
        'director_technical_affairs' => 'Director of Technical Affairs',
        'commission_president' => 'Commission President',
        'board_director' => 'Board Director',
        'audit_organ' => 'Audit Organ Member',
        'conflict_resolution_organ' => 'Conflict Resolution Organ Member',
        'legal_commission' => 'Legal Commission Member',
        'electoral_commission' => 'Electoral Commission Member',
        'ceo' => 'Chief Executive Officer',
        'hq_business' => 'Headquarters — Business Division',
        'hq_sport' => 'Headquarters — Sport Division',
        'hr_committee' => 'HR Committee Member',
        'member' => 'Member',
        'honorary_member' => 'Honorary Member',
        'fan_club_rep' => 'Fan Club Representative',
        'partner' => 'Partner',
        'player' => 'Player',
        'guest' => 'Guest',
    ];

    /** Bundle-level permission prefixes; each bundle delivery appends its own set. */
    private const BUNDLES = [
        'membership', 'hr', 'elections', 'disciplinary_legal',
        'financial_procurement_asset', 'fan_clubs', 'players_safeguarding',
        'operations_security_commissions',
    ];

    public function run(): void
    {
        foreach (self::BUNDLES as $bundle) {
            foreach (['view', 'create', 'update', 'delete', 'export', 'report'] as $action) {
                Permission::firstOrCreate(['name' => "{$bundle}.{$action}", 'guard_name' => 'web']);
            }
        }
        Permission::firstOrCreate(['name' => 'lookups.manage', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'users.manage', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'audit_logs.view', 'guard_name' => 'web']);

        foreach (self::ROLES as $code => $label) {
            Role::firstOrCreate(['name' => $code, 'guard_name' => 'web']);
        }

        // super_admin gets everything.
        Role::findByName('super_admin')->givePermissionTo(Permission::all());

        // audit_organ: read-only across financial/asset + audit trail.
        Role::findByName('audit_organ')->givePermissionTo([
            'financial_procurement_asset.view', 'financial_procurement_asset.export',
            'financial_procurement_asset.report', 'audit_logs.view',
        ]);

        // legal_commission + conflict_resolution_organ: disciplinary & legal bundle.
        foreach (['legal_commission', 'conflict_resolution_organ'] as $role) {
            Role::findByName($role)->givePermissionTo([
                'disciplinary_legal.view', 'disciplinary_legal.create',
                'disciplinary_legal.update', 'disciplinary_legal.report',
            ]);
        }

        // electoral_commission: elections bundle only.
        Role::findByName('electoral_commission')->givePermissionTo([
            'elections.view', 'elections.create', 'elections.update', 'elections.report',
        ]);

        // hr_committee: HR bundle.
        Role::findByName('hr_committee')->givePermissionTo([
            'hr.view', 'hr.create', 'hr.update', 'hr.delete', 'hr.export', 'hr.report',
        ]);

        // Full governance roles (president, VP, SG, treasurer, director, CEO,
        // board): broad read + module-specific write, kept simple here —
        // fine-tuned per bundle as each one is delivered.
        foreach (['president', 'vice_president', 'secretary_general', 'treasurer',
            'director_technical_affairs', 'ceo', 'board_director'] as $role) {
            Role::findByName($role)->givePermissionTo(
                Permission::where('name', 'like', '%.view')
                    ->orWhere('name', 'like', '%.report')
                    ->get()
            );
        }

        // Self-service roles: intentionally given no blanket permissions here.
        // Their access is enforced at the policy/controller level, scoped to
        // "own record only" (member sees own profile, player sees own file, etc.)
        // rather than via role permissions — added when each bundle is built.
    }
}
