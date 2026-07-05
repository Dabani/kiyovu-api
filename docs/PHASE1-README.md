# Phase 1 — Backend Skeleton: Auth, Roles, Lookup Engine, Audit Trail

## What this phase delivers
- Extended `users` table (name, phone, national ID, DOB, language, status, audit columns, soft deletes)
- `spatie/laravel-permission` roles/permissions tables + **22 IRR-derived roles** seeded
- Dedicated lookup tables: `lu_languages`, `lu_statuses`, `lu_document_classifications`
  (each dropdown = its own table, per rule #4 — future bundles add their own `lu_*` tables the same way)
- `role_scopes` — lets a role be tied to one specific entity (e.g. one commission, one fan club) instead of being global
- `documents` — polymorphic file-attachment table used by every future form
- `audit_logs` + `Auditable` trait/observer — **every domain model created from Bundle 1 onward just does `use Auditable;` and gets full create/update/delete/restore history for free**
- `AuthController` (Sanctum SPA cookie auth: login/logout/me) with rate limiting + status check
- `LookupController` — one generic `GET /api/lookups/{key}` endpoint the frontend calls for every dropdown; still backed by real per-concept tables, just served uniformly
- `WelcomeRegistrationNotification` + `AdminNewRegistrationNotification` — mail + in-app, ready to wire to Brevo SMTP

## Files delivered (drop into `kiyovu-api/`)
```
database/migrations/2026_01_01_000001_create_lu_languages_table.php
database/migrations/2026_01_01_000002_create_lu_statuses_table.php
database/migrations/2026_01_01_000003_create_lu_document_classifications_table.php
database/migrations/2026_01_01_000004_add_fields_to_users_table.php
database/migrations/2026_01_01_000005_create_permission_tables.php
database/migrations/2026_01_01_000006_create_role_scopes_table.php
database/migrations/2026_01_01_000007_create_documents_table.php
database/migrations/2026_01_01_000008_create_audit_logs_table.php
database/seeders/LuLanguageSeeder.php
database/seeders/LuStatusSeeder.php
database/seeders/LuDocumentClassificationSeeder.php
database/seeders/RoleAndPermissionSeeder.php
database/seeders/SuperAdminSeeder.php
database/seeders/DatabaseSeeder.php
app/Models/User.php
app/Models/RoleScope.php
app/Models/Document.php
app/Models/AuditLog.php
app/Models/Lookups/LuLanguage.php
app/Models/Lookups/LuStatus.php
app/Models/Lookups/LuDocumentClassification.php
app/Traits/IsLookup.php
app/Traits/Auditable.php
app/Observers/AuditableObserver.php
app/Http/Middleware/EnsureRoleScope.php
app/Http/Controllers/Api/Auth/AuthController.php
app/Http/Controllers/Api/LookupController.php
app/Notifications/WelcomeRegistrationNotification.php
app/Notifications/AdminNewRegistrationNotification.php
routes/api.php   (replace)
bootstrap/app.php   (replace)
```

## Setup commands
```bash
cd kiyovu-api
composer require laravel/sanctum spatie/laravel-permission
php artisan install:api

# Copy the files above into place, then:
php artisan migrate
php artisan db:seed
```

## .env additions
```
APP_URL=http://kiyovu-api.test
FRONTEND_URL=http://localhost:5173
SESSION_DOMAIN=localhost
SANCTUM_STATEFUL_DOMAINS=localhost:5173

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=<your brevo login email>
MAIL_PASSWORD=<your brevo smtp key>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@kiyovusports.rw
MAIL_FROM_NAME="Kiyovu Sports"

SUPER_ADMIN_PASSWORD=ChangeMe!2026
```
Also add to `config/app.php`:
```php
'frontend_url' => env('FRONTEND_URL', 'http://localhost:5173'),
```

## Verify it works
```bash
php artisan tinker
>>> App\Models\User::first()->getRoleNames();   // ["super_admin"]
>>> App\Models\Lookups\LuStatus::active()->ordered()->pluck('label_en');
```
```bash
curl -i -X POST http://kiyovu-api.test/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@kiyovusports.rw","password":"ChangeMe!2026"}'
```

## How every future bundle plugs in
1. New `lu_*` migration(s) for that bundle's dropdowns.
2. New domain model(s) — just add `use Auditable;` and it's tracked automatically.
3. New controller extending the same CRUD pattern (list w/ search+filter+pagination, export, PDF report).
4. Add its permission checks (`disciplinary_legal.view` etc. already seeded) via Policy classes.
5. Register routes inside the existing `auth:sanctum` group in `routes/api.php`.
6. Register the new lookup key(s) in `LookupController::MAP`.

Next up (Phase 2): the React + Mantine skeleton — brand theme, layout shell, role-based
dashboard routing, and the reusable `<DataTable>` component every one of the 53 screens will use.
