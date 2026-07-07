# Bundle 6 — Fan Clubs (FAN-001..008)

**Verified in this environment:** every PHP file `php -l` clean; frontend
`npm install`, `tsc --noEmit`, and full `vite build` all pass with the 8 new
screens wired in.

**Citations in this bundle were verified against the source text before
writing** (Articles 177, 179, 181, 182, 183 — confirmed by grepping the
actual document, not inferred). See the note at the end of this README about
citation corrections needed in earlier bundles.

## What this bundle delivers

All 8 fan club forms, sourced from Articles 174–183:

| Form | Screen | Backend table |
|---|---|---|
| FAN-001 | Recognition Application | `fan_clubs` |
| FAN-002 | Certificate of Recognition | `fan_clubs` (same table, dedicated issuing screen — same pattern as MEM-001/002) |
| FAN-003 | Annual Report | `fan_club_annual_reports` |
| FAN-004 | Annual Financial Summary | `fan_club_financial_summaries` |
| FAN-005 | Incident Report | `fan_incident_reports` |
| FAN-006 | Deregistration Warning | `fan_club_deregistration_warnings` |
| FAN-007 | Payment Confirmation | `fan_club_payment_confirmations` |
| FAN-008 | Membership Register | `fan_club_membership_registers` |

New lookup tables: `lu_incident_types` (pitch invasion, violence, prohibited
substances, discriminatory behaviour, property destruction — the actual
Art. 181 categories), `lu_fan_sanctions`.

## Design decisions worth knowing about
- **FAN-001/002 share one table**, same reasoning as MEM-001/002 and
  FAN-001/002 in earlier bundles: the application and the certificate are
  two stages of one lifecycle, not two independent entities.
- **FAN-005's 72-hour SLO report deadline is computed from `documented_on`**,
  not left blank for someone to calculate — `slo_report_due_on` is set
  server-side on create.
- **FAN-006 encodes the actual four-step Art. 182 procedure** (30-day
  remedy window → invited explanation → Executive Organ decision → GA
  appeal) as real fields with real dates, not a single flattened status.
- **FAN-008 surfaces the Art. 183 contribution formula live in the form** —
  RWF 1,000/member if ≥50 active members, otherwise a flat RWF 50,000
  minimum — as a computed preview, so whoever is filling out the register
  can sanity-check the fan club's next payment confirmation against it.

## Files (drop into your working copies)
```
kiyovu-api/database/migrations/2026_07_01_*.php     (9 migrations)
kiyovu-api/database/seeders/Lu{IncidentType,FanSanction}Seeder.php
kiyovu-api/database/seeders/RoleAndPermissionSeeder.php  (updated — hq_business/fan_club_rep grants)
kiyovu-api/database/seeders/DatabaseSeeder.php        (updated)
kiyovu-api/app/Models/Lookups/Lu{IncidentType,FanSanction}.php
kiyovu-api/app/Models/FanClubs/*.php                  (7 models)
kiyovu-api/app/Http/Controllers/Api/FanClubs/*.php    (8 controllers)
kiyovu-api/app/Http/Resources/FanClubs/SimpleModelResource.php
kiyovu-api/app/Http/Controllers/Api/LookupController.php  (updated)
kiyovu-api/routes/api.php                             (updated)
kiyovu-web/src/hooks/useFanClubOptions.ts
kiyovu-web/src/modules/fanclubs/*.tsx                 (8 screens + FanClubsModule shell)
kiyovu-web/src/App.tsx                                (updated — /fan-clubs/* route)
```

## Setup
```bash
cd kiyovu-api
php artisan migrate
php artisan db:seed
```
No new npm packages needed.

## ⚠️ Citation corrections still owed from Bundles 1, 2, 4, and 5

As flagged mid-project: earlier bundles contain fabricated or wrong article
numbers (some exceeding 255, which is the IRR's actual total article count —
an obvious tell I should have caught immediately). Per your instruction,
this is being tracked and will be delivered as a single plain-text
find/replace map after the final bundle, rather than patched bundle-by-bundle.
Bundle 3 (Elections) and this bundle (6) were both verified clean.

## Progress so far
Phase 1 → Phase 2 → Bundle 1 (9) → Bundle 2 (7) → Bundle 3 (5) → Bundle 4 (6)
→ Bundle 5 (7) → **Bundle 6 (8) = 42 of 53 screens delivered.**

## Next up
**Bundle 7 — Players & Safeguarding** (PLAYER-001–004, SAFE-001–003, per
your bundle table).
