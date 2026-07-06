<div align="center">

<img src="docs/assets/kiyovu-crest.png" alt="Kiyovu Sports Association" width="96" />

# Kiyovu IRMS — API

**Internal Rules Management System for Kiyovu Sports Association**
Laravel backend powering the digitisation of Kiyovu Sports' Internal Rules & Regulations (IRR)

[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com)
[![License](https://img.shields.io/badge/License-Proprietary-lightgrey)](#license)

</div>

---

## About

Kiyovu Sports Association's Internal Rules & Regulations run to 255 articles
covering governance, membership, HR, elections, discipline, finance, fan
clubs, player welfare, and operations. This API turns that document into a
working system of record: **53 data-entry forms across 8 module bundles**,
each backed by real database tables, role-based access control, an
immutable audit trail, and exportable reports — rather than a generic CMS
bolted onto a legal text.

The companion frontend lives in [`kiyovu-web`](../kiyovu-web).

## Core design principles

- **No hardcoded option lists.** Every dropdown, status, and category in
  the system is backed by its own `lu_*` database table, seeded from the
  IRR's actual defined values — not an enum, not a JSON blob. Adding a new
  fee tier or sanction type is a data change, not a deploy.
- **Every domain model is audited automatically.** A single `Auditable`
  trait gives any model full create/update/delete/restore history in
  `audit_logs`, attributed to the acting user, with no per-model
  boilerplate.
- **Role-based access is enforced server-side, not suggested by the UI.**
  Business rules from the IRR — co-signature thresholds, quotation minimums,
  GA-approval triggers, anonymity protection for whistleblower reports — are
  checked in controllers, not left to the frontend to get right.
- **Bundle-anchored data model.** Each of the 8 module bundles has one
  anchor entity (e.g. `members`, `players`, `disciplinary_cases`) that later
  forms in the same bundle reference by foreign key, mirroring how the IRR
  itself structures related forms around a single case or record.

## Tech stack

| Layer | Choice |
|---|---|
| Framework | Laravel 11 (PHP 8.3) |
| Database | MySQL 8.0 |
| Auth | Laravel Sanctum (SPA cookie auth) |
| Authorization | [spatie/laravel-permission](https://spatie.be/docs/laravel-permission) — 22 roles derived from the IRR's governance structure |
| Exports | maatwebsite/excel (Excel), barryvdh/laravel-dompdf (PDF reports) |
| Notifications | Laravel Notifications + Mail, via Brevo SMTP |

## Module bundles

| # | Bundle | Forms | Screens |
|---|---|---|---|
| 1 | Membership & Honorary | MEM-001–007, HON-001–002 | 9 |
| 2 | HR & Recruitment | HR-001–007 | 7 |
| 3 | Elections | ELEC-001–005 | 5 |
| 4 | Disciplinary & Legal | DISC-001/002/003/005, LEG-001/002 | 6 |
| 5 | Financial, Procurement & Asset | FIN-001/003, PROC-002/003/004, ASSET-001/003 | 7 |
| 6 | Fan Clubs | FAN-001–008 | 8 |
| 7 | Players & Safeguarding | PLAYER-001–004, SAFE-001–003 | 7 |
| 8 | Operations, Security & Commissions | OPS-001, SEC-001, COMM-001/002 | 4 |
| | **Total** | | **53** |

## Requirements

- PHP 8.3+ with `fileinfo`, `gd`, `intl`, `pdo_mysql`, `mbstring`, `openssl`, `zip`, `curl` extensions
- Composer 2.8+
- MySQL 8.0+
- A local dev environment such as [Laragon](https://laragon.org) (Windows) or Valet/Sail (macOS/Linux)

## Getting started

```bash
git clone <this-repo> kiyovu-api
cd kiyovu-api
composer install
cp .env.example .env
php artisan key:generate
```

### Configure `.env`

```dotenv
APP_URL=http://kiyovu-api.test
FRONTEND_URL=http://localhost:5173

DB_DATABASE=kiyovu_irms
DB_USERNAME=root
DB_PASSWORD=

SESSION_DOMAIN=localhost
SANCTUM_STATEFUL_DOMAINS=localhost:5173

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=<your Brevo login>
MAIL_PASSWORD=<your Brevo SMTP key>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@kiyovusports.rw
MAIL_FROM_NAME="Kiyovu Sports"

SUPER_ADMIN_PASSWORD=ChangeMe!2026
```

> **Note:** `SUPER_ADMIN_PASSWORD` is read once, at the moment
> `db:seed` runs. Changing it afterwards in `.env` has no effect on the
> already-created account — re-run `php artisan db:seed --class=SuperAdminSeeder`
> (safe to repeat; it uses `updateOrCreate`) if you need to rotate it.

### Create the database and run migrations

```bash
mysql -u root -e "CREATE DATABASE kiyovu_irms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
php artisan db:seed
```

### Serve

```bash
php artisan serve
# or point Laragon/Valet at /public with the kiyovu-api.test domain
```

### Verify

```bash
curl -i -X POST http://kiyovu-api.test/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@kiyovusports.rw","password":"ChangeMe!2026"}'
```

## Roles & permissions

Roles map directly to the IRR's governance structure rather than a generic
admin/user split — `president`, `secretary_general`, `treasurer`,
`hr_committee`, `electoral_commission`, `legal_commission`,
`conflict_resolution_organ`, `hq_business`, `hq_sport`, self-service `member`,
`fan_club_rep`, `player`, and 10 others. Each module bundle's permissions
follow the pattern `{bundle}.{view|create|update|delete|export|report}`,
seeded in `RoleAndPermissionSeeder`.

Self-service roles are scoped to their own records at the query level (see
`app/Http/Controllers/Api/Concerns/ScopesToOwnMember.php` for the pattern),
not just hidden in the UI.

## Project structure

```
app/
├── Models/
│   ├── Lookups/              # one model per lu_* dropdown table
│   ├── Membership/ Hr/ Elections/ Disciplinary/ Financial/
│   ├── FanClubs/ Players/ Operations/    # one namespace per bundle
│   └── User.php, RoleScope.php, Document.php, AuditLog.php
├── Http/
│   ├── Controllers/Api/
│   │   ├── Concerns/BaseModuleController.php   # shared list/search/filter/paginate/report logic
│   │   └── {Bundle}/{Form}Controller.php
│   └── Resources/{Bundle}/SimpleModelResource.php
├── Traits/Auditable.php, IsLookup.php
├── Policies/
└── Notifications/
database/
├── migrations/                # numbered by bundle: 2026_0N_01_...
└── seeders/                   # lu_* lookup data + roles/permissions
resources/views/
├── layouts/report.blade.php   # shared brand-styled PDF header
└── reports/                  # per-module and generic PDF report views
routes/api.php
```

## API conventions

- Every resource follows REST conventions: `GET/POST /{resource}`,
  `PUT /{resource}/{id}`, `DELETE /{resource}/{id}`.
- List endpoints support `?search=`, per-bundle `?{column}=` filters,
  `?page=`, `?per_page=`.
- Every module exposes `GET /{resource}/report?period=daily|weekly|monthly|quarterly|annual|custom`,
  returning a generated PDF.
- All dropdown data is served uniformly via `GET /api/lookups/{key}` — see
  `LookupController::MAP` for the full key list.

## Testing & verification status

Every migration, model, controller, and seeder in this codebase has been
syntax-checked (`php -l`) and reviewed for logical consistency during
development. **No automated test suite (PHPUnit/Pest) has been written
yet, and migrations have not been run against a live database in this
environment** — treat this as a solid, reviewed starting point, not a
production-certified codebase. Contributions adding feature tests are
very welcome.

## Documentation

Per-bundle delivery notes (design decisions, known gaps, verified IRR
article citations) live in [`docs/`](docs).

## License

Proprietary — © Kiyovu Sports Association. Not licensed for reuse outside
the organisation without permission.
