# Bundle 5 — Financial, Procurement & Asset (FIN-001/003, PROC-002/003/004, ASSET-001/003)

**Verified in this environment:** every PHP file `php -l` clean; frontend
`npm install`, `tsc --noEmit`, and full `vite build` all pass with the 7 new
screens wired in.

## What this bundle delivers

All 7 forms, sourced from Articles 888 (expenditure authorization), 898
(procurement thresholds), 901 (asset register), 102 (disposal), and 1013
(partnership value thresholds):

| Form | Screen | Backend table |
|---|---|---|
| FIN-001 | Payment Authorizations | `payment_authorizations` |
| FIN-003 | Petty Cash Vouchers | `petty_cash_vouchers` |
| PROC-002 | Requests for Quotations | `procurement_rfqs` |
| PROC-003 | Competitive Tenders | `procurement_tenders` |
| PROC-004 | Written Contracts | `written_contracts` |
| ASSET-001 | Asset Register | `asset_register` |
| ASSET-003 | Asset Handovers | `asset_handovers` |

New lookup tables: `lu_expenditure_tiers` (the real four-tier Art. 888
framework — Routine/Significant/Major/Capital, each with its actual RWF
range and required authoriser), `lu_asset_categories`, `lu_contract_types`
(procurement vs partnership — PROC-004 genuinely serves both, per Art. 1013).

## Design decisions worth knowing about — this bundle has the most business-rule enforcement so far
- **The expenditure tier is computed, not selected.** `PaymentAuthorizationController::store()`
  looks up the correct tier from the amount and rejects the request outright
  if a Significant/Major/Capital-tier payment is submitted without a
  Treasurer co-signature — Art. 888's authorization ladder can't be
  bypassed by picking a different tier in a dropdown, because there is no
  such dropdown.
- **PROC-002 won't let you record a selected vendor with fewer than 3
  quotations on file** — Art. 898's "written quotations from ≥3 vendors"
  requirement is checked server-side on every update, not just suggested by
  the form.
- **PROC-004's GA notification/approval flags are computed from the monthly
  value**, not manually ticked — mirrors the same pattern used for LEG-001's
  urgency escalation in Bundle 4. A partnership at or above RWF 50M/month
  can't be marked `approved` without `ga_approved` being true first.
- **ASSET-003 syncs the asset register automatically.** Once both
  `outgoing_signed` and `incoming_signed` are true on a handover, the
  linked `asset_register` row's `custodian_name` updates to match — so the
  register never silently drifts out of sync with the last completed
  handover.
- **FIN-001 and FIN-003 are strictly separated by amount**, not by user
  choice: FIN-001 validates `amount_rwf > 50000`, FIN-003 validates
  `amount_rwf <= 50000`. Submitting a petty-cash-sized amount through
  Payment Authorizations is rejected server-side, and vice versa.

## Files (drop into your working copies)
```
kiyovu-api/database/migrations/2026_06_01_*.php     (10 migrations)
kiyovu-api/database/seeders/Lu{ExpenditureTier,AssetCategory,ContractType}Seeder.php
kiyovu-api/database/seeders/DatabaseSeeder.php        (updated)
kiyovu-api/app/Models/Lookups/Lu{ExpenditureTier,AssetCategory,ContractType}.php
kiyovu-api/app/Models/Financial/*.php                 (7 models)
kiyovu-api/app/Http/Controllers/Api/Financial/*.php   (7 controllers)
kiyovu-api/app/Http/Resources/Financial/SimpleModelResource.php
kiyovu-api/app/Http/Controllers/Api/LookupController.php  (updated)
kiyovu-api/routes/api.php                             (updated)
kiyovu-web/src/hooks/useAssetOptions.ts
kiyovu-web/src/modules/financial/*.tsx                (7 screens + FinancialModule shell)
kiyovu-web/src/App.tsx                                (updated — /financial/* route)
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
→ **Bundle 5 (7) = 34 of 53 screens delivered.**

## Next up
**Bundle 6 — Fan Clubs** (FAN-001–008, per your bundle table) — the largest
remaining single bundle at 8 forms.
