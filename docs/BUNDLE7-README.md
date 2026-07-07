# Bundle 7 — Players & Safeguarding (PLAYER-001..004, SAFE-001..003)

**Verified in this environment:** every PHP file `php -l` clean; frontend
`npm install`, `tsc --noEmit`, and full `vite build` all pass with the 7 new
screens wired in.

**IRR citations verified against source text:** Art. 195–198 (player
registration, contracts, transfers/loans, anti-doping), Art. 200–201, 211,
212, 215 (safeguarding). All confirmed by grepping the actual document.

**One citation flagged, not verified:** the frontend references "Art. 74 of
the Constitution" for medical clearance. That's a different document from
the IRR I've been auditing against, and I don't have its text to check —
so unlike every other citation in this bundle, that one is presented as-is
from the earlier extraction rather than freshly confirmed. Worth a quick
manual check against the actual Constitution before relying on it.

## What this bundle delivers

All 7 forms, sourced from Articles 195–198 (player registration framework)
and 200–215 (safeguarding framework):

| Form | Screen | Backend table |
|---|---|---|
| PLAYER-001 | Player Contracts | `player_contracts` |
| PLAYER-002 | Player Registration | `players` |
| PLAYER-003 | Loan Agreements | `player_loan_agreements` |
| PLAYER-004 | Anti-Doping Declaration | `anti_doping_declarations` |
| SAFE-001 | Safeguarding Concern Report | `safeguarding_concern_reports` |
| SAFE-002 | Parental/Guardian Consent | `parental_consent_forms` |
| SAFE-003 | Code of Conduct Acknowledgement | `code_of_conduct_acknowledgements` |

New lookup tables: `lu_player_teams` (senior/reserve/academy),
`lu_loan_directions`, `lu_signatory_types`.

## Design decisions worth knowing about
- **`players` (PLAYER-002) is the anchor table**, same pattern as every
  prior bundle — PLAYER-001/003/004 and SAFE-002 all reference a player by FK.
- **Minor status is computed from date of birth, not self-declared.**
  `PlayerController::store()` calculates `is_minor` from `date_of_birth` at
  save time — nobody can register a 16-year-old as an adult by leaving a
  checkbox unticked.
- **SAFE-001 reuses the exact anonymity-protection pattern from Bundle 4's
  whistleblower reports** — `reporter_name` is forcibly nulled server-side
  when `is_anonymous` is true. Two independent modules, same rule, same
  enforcement point, not duplicated logic with room to drift apart.
- **SAFE-001 deliberately avoids a full child-identity field.** `subject_reference`
  is free text and optional, kept minimal on purpose — this is a form about
  documenting a concern and triggering the right escalation, not a place to
  accumulate sensitive detail about a child that doesn't need to live in a
  general-purpose records system.
- **SAFE-003 links to `lu_hq_positions` from Bundle 2** rather than
  duplicating a position list — a Head Coach or Child Safeguarding Officer
  signing their code of conduct references the same position record used
  everywhere else in the system.

## Files (drop into your working copies)
```
kiyovu-api/database/migrations/2026_08_01_*.php     (10 migrations)
kiyovu-api/database/seeders/Lu{PlayerTeam,LoanDirection,SignatoryType}Seeder.php
kiyovu-api/database/seeders/RoleAndPermissionSeeder.php  (updated — hq_sport/director_technical_affairs grants)
kiyovu-api/database/seeders/DatabaseSeeder.php        (updated)
kiyovu-api/app/Models/Lookups/Lu{PlayerTeam,LoanDirection,SignatoryType}.php
kiyovu-api/app/Models/Players/*.php                   (7 models)
kiyovu-api/app/Http/Controllers/Api/Players/*.php     (7 controllers)
kiyovu-api/app/Http/Resources/Players/SimpleModelResource.php
kiyovu-api/app/Http/Controllers/Api/LookupController.php  (updated)
kiyovu-api/routes/api.php                             (updated)
kiyovu-web/src/hooks/usePlayerOptions.ts
kiyovu-web/src/modules/players/*.tsx                  (7 screens + PlayersSafeguardingModule shell)
kiyovu-web/src/App.tsx                                (updated — /players-safeguarding/* route)
```

## Setup
```bash
cd kiyovu-api
php artisan migrate
php artisan db:seed
```
No new npm packages needed.

## Progress so far
Phase 1 → Phase 2 → Bundle 1 (9) → Bundle 2 (7) → Bundle 3 (5) → Bundle 4 (6)
→ Bundle 5 (7) → Bundle 6 (8) → **Bundle 7 (7) = 49 of 53 screens delivered.**

## Next up
**Bundle 8 — Operations, Security & Commissions** (OPS-001, SEC-001,
COMM-001/002, per your bundle table) — the final bundle. After that, the
consolidated citation find/replace map for Bundles 1, 2, 4, and 5 as agreed.
