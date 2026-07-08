# Bundle 1 — Membership & Honorary (MEM-001..007, HON-001/002)

**Verified in this environment:**
- Backend: every PHP file (migrations, models, controllers, requests, resources, policies, seeders) passes `php -l` — genuinely linted this time (see note below).
- Frontend: `npm install`, `tsc --noEmit`, and a full `vite build` all pass with the 9 new screens wired in.

> **Correction from Phase 1:** the earlier "lint clean" claim for Phase 1 was
> based on a shell command that never actually ran (`which php && ...` short-
> circuited because PHP wasn't installed in this sandbox). I've since
> installed `php-cli` here and re-linted **all** Phase 1 + Bundle 1 files for
> real — all clean. Flagging this so the record is accurate.

## What this bundle delivers

All 9 forms from your bundle table, each as its own screen, backed by real IRR content (Articles 10–24 for membership/honorary rules, 165–172 for the six fee tiers):

| Form | Screen | Backend table |
|---|---|---|
| MEM-001 | Membership Applications | `members` |
| MEM-002 | Membership Acknowledgement | `members` (same table, dedicated queue/action screen) |
| MEM-003 | Member Information Requests | `member_information_requests` |
| MEM-004 | Inactive Status Requests | `member_inactive_status_requests` |
| MEM-005 | Fee Waiver Requests | `member_fee_waiver_requests` |
| MEM-006 | Resignations | `member_resignations` |
| MEM-007 | Reinstatement Requests | `member_reinstatement_requests` |
| HON-001 | Honorary Nominations | `honorary_nominations` |
| HON-002 | Honorary Nomination Dossiers | `honorary_nomination_dossiers` |

New dedicated lookup tables (rule #4 — no enums, no JSON):
`lu_membership_categories` (Founder/Adherent), `lu_fee_tiers` (the real six
tiers — INZIRA through UMUTWARE, with actual RWF ranges from Articles
166–171), `lu_payment_methods` (Art. 172), `lu_nominee_types`. Plus four new
member-scoped rows added to `lu_statuses` (`member_active/inactive/
suspended/terminated`).

## Backend files (drop into `kiyovu-api/`)
```
database/migrations/2026_02_01_*.php          (12 migrations)
database/seeders/LuMembershipCategorySeeder.php
database/seeders/LuFeeTierSeeder.php
database/seeders/LuPaymentMethodSeeder.php
database/seeders/LuNomineeTypeSeeder.php
database/seeders/LuStatusSeeder.php            (updated — member_* codes added)
database/seeders/RoleAndPermissionSeeder.php   (updated — self-service grants)
database/seeders/DatabaseSeeder.php            (updated)
app/Models/Lookups/{LuMembershipCategory,LuFeeTier,LuPaymentMethod,LuNomineeType}.php
app/Models/Membership/*.php                    (9 models)
app/Http/Controllers/Controller.php            (updated — adds AuthorizesRequests)
app/Http/Controllers/Api/Concerns/BaseModuleController.php   (updated — baseQuery hook, genericReport)
app/Http/Controllers/Api/Concerns/ScopesToOwnMember.php       (new)
app/Http/Controllers/Api/Membership/*.php      (9 controllers)
app/Http/Requests/Membership/{Store,Update}MemberRequest.php
app/Http/Resources/Membership/{MemberResource,SimpleModelResource}.php
app/Policies/MemberPolicy.php
app/Http/Controllers/Api/LookupController.php  (updated — Bundle 1 keys)
resources/views/layouts/report.blade.php
resources/views/reports/members.blade.php
resources/views/reports/simple-list.blade.php
routes/api.php                                 (replace — Bundle 1 routes added)
```

## Frontend files (drop into `kiyovu-web/`)
```
src/hooks/useCrudMutations.ts
src/hooks/useMemberOptions.ts
src/hooks/useLookup.ts                         (updated — useLookupSelect helper)
src/modules/membership/*.tsx                   (9 screens + MembershipModule shell)
src/App.tsx                                    (updated — /membership/* route)
```

## Setup
```bash
cd kiyovu-api
php artisan migrate
php artisan db:seed
```
No new npm packages needed for Bundle 1 — everything reuses Phase 2's stack.

## Design decisions worth knowing about
- **MEM-001 and MEM-002 share one table but are deliberately two screens.**
  MEM-001 is the application intake form; MEM-002 is the Secretary General's
  acknowledgement queue (Art. 15) — a filtered, action-oriented view, not a
  generic edit form. This matches "each form gets its own screen" without
  creating a redundant second table for what's really one lifecycle.
- **Self-service scoping is real, not cosmetic.** A `member`-role user hitting
  `GET /api/members` or any of the MEM-003..007 endpoints only ever sees rows
  tied to their own account, via `ScopesToOwnMember` — not just hidden by the
  UI. Governance roles (`super_admin`, `president`, `secretary_general`,
  `hr_committee`) see everything.
- **Business rules enforced server-side, not just in the form:** the MEM-004
  two-year inactive-status cap (Art. 17) is computed on save; HON-001 refuses
  to move a nomination to `approved` without both Executive Organ *and* Board
  endorsement (Art. 37); approving a MEM-006 resignation or MEM-007
  reinstatement automatically updates the member's registry status.
- **PDF reports** use a shared brand-styled Blade layout (`layouts/report.blade.php`)
  with the green header band — `members.blade.php` is bespoke for the richer
  registry columns, the other 8 forms share one generic `simple-list.blade.php`
  driven by a column map, since hand-writing 8 near-identical Blade files
  added no value.

## Known gap to close in a later pass
`member_id` on MEM-003..007 create forms currently lets any authorized staff
member pick *any* member from the dropdown — correct for governance roles,
but a self-service `member` submitting their own resignation, for example,
should have that field pre-filled and locked to their own record rather than
open. Flagging honestly rather than quietly shipping it: worth a small
follow-up pass (auto-fill + disable the field when `role === 'member'`)
before this goes to real self-service users.

## Next up
**Phase 4 / Bundle 2 — HR & Recruitment** (HR-001–007, per your bundle table).
