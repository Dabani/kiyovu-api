# Bundle 4 — Disciplinary & Legal (DISC-001/002/003/005, LEG-001/002)

**Verified in this environment:** every PHP file `php -l` clean; frontend
`npm install`, `tsc --noEmit`, and full `vite build` all pass with the 6 new
screens wired in.

## What this bundle delivers

All 6 forms, sourced from Articles 53, 274, 613, 907, 1125, and 1140–1158
(conflict resolution procedure, whistleblower protections, legal matter
handling, disciplinary chamber process):

| Form | Screen | Backend table |
|---|---|---|
| DISC-001 | Disciplinary Cases (intake) | `disciplinary_cases` |
| DISC-002 | Disciplinary Decisions | `disciplinary_decisions` |
| DISC-003 | Notices (allegations / hearing) | `disciplinary_notices` |
| DISC-005 | Whistleblower Reports | `whistleblower_reports` |
| LEG-001 | Legal Matter Intake | `legal_matter_intakes` |
| LEG-002 | Legal Case Register | `legal_case_register` |

*(DISC-004 isn't in your bundle table, so it isn't in this delivery — the 6
screens here match your image exactly.)*

New lookup tables: `lu_disciplinary_case_sources`, `lu_disciplinary_sanctions`,
`lu_notice_types`, `lu_whistleblower_categories`, `lu_legal_forums` (FERWAFA,
CAF, FIFA, CAS/TAS, Rwandan courts, other — the actual forums named in
Art. 613), `lu_legal_urgency`.

## Design decisions worth knowing about
- **`disciplinary_cases` is the pipeline spine** for DISC-002 (decisions) and
  DISC-003 (notices) — same anchor-table pattern as every prior bundle.
  DISC-005 (whistleblower reports) can optionally link to a case once one is
  opened, but stands alone by design, since most whistleblower reports don't
  yet have a formal case when first submitted.
- **Anonymity is enforced server-side, not just hidden in the UI.**
  `WhistleblowerReportController::store()` forcibly nulls `reporter_name`
  whenever `is_anonymous` is true, regardless of what the request body
  contains — Art. 274's anonymity protection doesn't depend on the frontend
  behaving correctly.
- **The "reported to President" flag on LEG-001 is computed, not manually
  set.** Art. 613 says any matter with a deadline under 14 days must be
  escalated immediately; `LegalMatterIntakeController::update()` calculates
  this from `deadline_date` on every save rather than trusting a checkbox
  someone might forget to tick.
- **LEG-002's confidentiality classification reuses `lu_document_classifications`**
  from Phase 1 rather than inventing a parallel list — Art. 613 explicitly
  ties the case register to confidentiality protections, and that concept
  already has a home.

## Files (drop into your working copies)
```
kiyovu-api/database/migrations/2026_05_01_*.php     (12 migrations)
kiyovu-api/database/seeders/Lu{DisciplinaryCaseSource,DisciplinarySanction,NoticeType,WhistleblowerCategory,LegalForum,LegalUrgency}Seeder.php
kiyovu-api/database/seeders/DatabaseSeeder.php        (updated)
kiyovu-api/app/Models/Lookups/Lu{DisciplinaryCaseSource,DisciplinarySanction,NoticeType,WhistleblowerCategory,LegalForum,LegalUrgency}.php
kiyovu-api/app/Models/Disciplinary/*.php              (6 models)
kiyovu-api/app/Http/Controllers/Api/Disciplinary/*.php (6 controllers)
kiyovu-api/app/Http/Resources/Disciplinary/SimpleModelResource.php
kiyovu-api/app/Http/Controllers/Api/LookupController.php  (updated)
kiyovu-api/routes/api.php                             (updated)
kiyovu-web/src/hooks/useDisciplinaryCaseOptions.ts
kiyovu-web/src/modules/disciplinary/*.tsx             (6 screens + DisciplinaryLegalModule shell)
kiyovu-web/src/App.tsx                                (updated — /disciplinary-legal/* route)
```

## Setup
```bash
cd kiyovu-api
php artisan migrate
php artisan db:seed
```
No new npm packages needed.

## Progress so far
Phase 1 → Phase 2 → Bundle 1 (9) → Bundle 2 (7) → Bundle 3 (5) →
**Bundle 4 (6) = 27 of 53 screens delivered — just past the halfway mark.**

## Next up
**Bundle 5 — Financial, Procurement & Asset** (FIN-001/003, PROC-002/003/004,
ASSET-001/003, per your bundle table).
