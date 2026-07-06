# Bundle 2 — HR & Recruitment (HR-001..007)

**Verified in this environment:** every PHP file `php -l` clean; frontend
`npm install`, `tsc --noEmit`, and full `vite build` all pass with the 7 new
screens wired in.

## What this bundle delivers

All 7 HR forms, sourced from Articles 116–144 (recruitment process, HQ
Personnel framework, HR Committee duties, conflict of interest, gifts):

| Form | Screen | Backend table |
|---|---|---|
| HR-001 | Employment Contracts | `hr_employment_contracts` |
| HR-002 | Background Check Consents | `hr_background_checks` |
| HR-003 | Conflict of Interest Declarations | `hr_conflict_of_interest_declarations` |
| HR-004 | Gift Declarations | `hr_gift_declarations` |
| HR-005 | Shortlisting Criteria & Scores | `recruitment_candidates` (candidate intake + shortlisting combined) |
| HR-006 | Interview Scoring Matrix | `hr_interview_scores` |
| HR-007 | Appointment Recommendations | `hr_appointment_recommendations` |

New lookup tables: `lu_hq_positions` (all 15 real HQ Personnel titles from
Articles 119–133 — CEO down to Child Safeguarding Officer, each tagged with
its division and whether it involves contact with minors), `lu_employment_types`,
`lu_gift_dispositions`. Plus six new `lu_statuses` rows for the recruitment
pipeline (`recruitment_applied` → `recruitment_appointed`/`recruitment_rejected`).

## Design decisions worth knowing about
- **`recruitment_candidates` is the pipeline spine, not a form of its own.**
  HR-002 (background checks), HR-006 (interview scores), and HR-007
  (recommendations) all optionally or required-ly reference a candidate by
  FK, mirroring how Bundle 1's `members` table anchored MEM-003..007. HR-005
  *is* the candidate-intake-plus-shortlisting screen — same pattern as
  MEM-001/002 sharing the `members` table.
- **HR-002 works for candidates and existing staff alike.** `candidate_id` is
  nullable — the Child Safeguarding Officer's annual re-checks on existing
  Sport Division staff (Art. 111) don't need a fabricated candidate record.
- **Business rules enforced server-side:** HR-004 requires the value exceed
  RWF 30,000 before the record is even accepted (Art. 128's actual
  threshold, not just a UI hint); HR-003 auto-computes `next_annual_update_due`
  as declaration date + 1 year (Art. 144).
- **`lu_hq_positions.involves_minors`** is there so a future pass can
  auto-flag HR-002 as mandatory when a position is selected on HR-001 or
  HR-005 — wiring worth adding once the frontend has a reason to cross-reference
  two forms live (not done yet, flagging rather than silently deferring).

## Files (drop into your working copies)
```
kiyovu-api/database/migrations/2026_03_01_*.php     (10 migrations)
kiyovu-api/database/seeders/Lu{HqPosition,EmploymentType,GiftDisposition}Seeder.php
kiyovu-api/database/seeders/LuStatusSeeder.php       (updated)
kiyovu-api/database/seeders/DatabaseSeeder.php       (updated)
kiyovu-api/app/Models/Lookups/Lu{HqPosition,EmploymentType,GiftDisposition}.php
kiyovu-api/app/Models/Hr/*.php                       (8 models)
kiyovu-api/app/Http/Controllers/Api/Hr/*.php         (7 controllers)
kiyovu-api/app/Http/Resources/Hr/SimpleModelResource.php
kiyovu-api/app/Http/Controllers/Api/LookupController.php  (updated)
kiyovu-api/routes/api.php                             (updated)
kiyovu-web/src/hooks/useCandidateOptions.ts
kiyovu-web/src/modules/hr/*.tsx                       (7 screens + HrModule shell)
kiyovu-web/src/App.tsx                                (updated — /hr/* route)
```

## Setup
```bash
cd kiyovu-api
php artisan migrate
php artisan db:seed
```
No new npm packages needed.

## Next up
**Bundle 3 — Elections** (ELEC-001–005, per your bundle table).
