# Bundle 3 — Elections (ELEC-001..005)

**Verified in this environment:** every PHP file `php -l` clean; frontend
`npm install`, `tsc --noEmit`, and full `vite build` all pass with the 5 new
screens wired in.

## What this bundle delivers

All 5 election forms, sourced from Articles 64–75 (Electoral Commission,
nomination process, voting, results, disputes):

| Form | Screen | Backend table |
|---|---|---|
| ELEC-001 | Nominations | `election_nominations` |
| ELEC-002 | Tally Sheets | `election_tally_sheets` |
| ELEC-003 | Results Certification | `election_results_certifications` |
| ELEC-004 | Handover Reports | `election_handover_reports` |
| ELEC-005 | Election Disputes | `election_disputes` |

New lookup tables: `lu_elected_positions` (President, VP, Secretary General,
Treasurer, Director of Technical Affairs, Board Director, Audit Organ
Member — the actual elected roles, each tagged with term length and whether
a criminal record certificate is required, per Art. 68's President/VP-only
rule) and `lu_dispute_grounds` (the limited grounds Art. 71 actually allows —
procedural error, post-election disqualifying discovery, prohibited campaign
conduct, electoral fraud — not an open-ended list).

## Design decisions worth knowing about
- **`election_nominations` is the pipeline spine**, same pattern as
  `members` and `recruitment_candidates` in the prior two bundles.
  ELEC-002 and ELEC-003 both reference a nomination by FK.
- **The 500-word cap on the statement of intent (Art. 68) is enforced twice**:
  live word-count feedback in the React form, and a server-side check in
  `ElectionNominationController::store()` that rejects the request outright
  if exceeded — the UI limit alone wouldn't stop a direct API call.
- **ELEC-002 tally sheets are one row per candidate**, matching how a real
  tally sheet reports votes per name on the ballot for a given position;
  `invalid_ballots_count` is captured once per position and — for
  simplicity in this pass — duplicated across that position's candidate
  rows rather than living in a separate summary table. Worth normalizing
  into its own `election_position_summaries` table if a future audit needs
  a single authoritative invalid-ballot figure instead of N duplicates.
- **ELEC-003 requires all three Electoral Commission member names** as
  plain text fields rather than FKs to a not-yet-built commission-membership
  table — Bundle 8 (Operations, Security & Commissions) is where commission
  membership itself gets modeled; this bundle doesn't reach ahead of itself.

## Files (drop into your working copies)
```
kiyovu-api/database/migrations/2026_04_01_*.php     (7 migrations)
kiyovu-api/database/seeders/Lu{ElectedPosition,DisputeGround}Seeder.php
kiyovu-api/database/seeders/DatabaseSeeder.php        (updated)
kiyovu-api/app/Models/Lookups/Lu{ElectedPosition,DisputeGround}.php
kiyovu-api/app/Models/Elections/*.php                 (5 models)
kiyovu-api/app/Http/Controllers/Api/Elections/*.php   (5 controllers)
kiyovu-api/app/Http/Resources/Elections/SimpleModelResource.php
kiyovu-api/app/Http/Controllers/Api/LookupController.php  (updated)
kiyovu-api/routes/api.php                             (updated)
kiyovu-web/src/hooks/useNominationOptions.ts
kiyovu-web/src/modules/elections/*.tsx                (5 screens + ElectionsModule shell)
kiyovu-web/src/App.tsx                                (updated — /elections/* route)
```

## Setup
```bash
cd kiyovu-api
php artisan migrate
php artisan db:seed
```
No new npm packages needed.

## Progress so far
Phase 1 (backend skeleton) → Phase 2 (frontend skeleton) → Bundle 1 (9
screens) → Bundle 2 (7 screens) → **Bundle 3 (5 screens) = 21 of 53 screens
delivered.**

## Next up
**Bundle 4 — Disciplinary & Legal** (DISC-001/002/003/005, LEG-001/002, per
your bundle table).
