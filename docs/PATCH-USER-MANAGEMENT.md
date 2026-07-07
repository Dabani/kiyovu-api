# User Management — Patch

## Why this was missing

`/users` was referenced in the sidebar since Phase 2 (`navConfig.ts`,
super_admin-only) and the `permission` `users.manage` was seeded since
Phase 1 — but the actual screen and its backend controller were never
built. User/account administration isn't one of the 53 IRR module forms,
so it fell outside the bundle-by-bundle delivery and got missed. This
patch fills that gap.

## What this adds

**Backend:**
- `UserController` — full CRUD over accounts (`GET/POST /api/users`,
  `PUT/DELETE /api/users/{id}`), scoped to `users.manage` permission
  (super_admin only, per the existing role seed).
- `RoleController` — `GET /api/roles`, lists all 22 seeded roles for the
  role-assignment multi-select.
- `POST /api/users/{id}/reset-password` — issues a new random temporary
  password for an account.
- Wires up `WelcomeRegistrationNotification` and
  `AdminNewRegistrationNotification` — both existed since Phase 1 but were
  never actually triggered anywhere until now. Creating a user now emails
  the new user and every super_admin.

**Frontend:**
- `UsersPage.tsx` — list (search/filter/paginate) + create/edit modal with
  role multi-select, using the same `<DataTable>` pattern as every other
  screen.
- `/users` route registered in `App.tsx`, wrapped in the `RequireRole`
  guard — which existed in `ProtectedRoute.tsx` since Phase 2 but was
  never actually used on any route until now.
- A password-reset button per row, and a one-time "copy this password"
  screen shown right after creating a new account.

## Known limitation, stated plainly

There is **no self-service password reset or "forgot password" flow**.
Account creation and password resets both generate a random temporary
password that only the admin doing the action ever sees (once, in the UI).
The user has no way to change it themselves yet. If that's needed soon,
it's a reasonably small addition (a `password_reset_tokens` flow via
Laravel's built-in `Illuminate\Auth\Passwords` broker) — flagging it now
rather than pretending this patch is a complete auth lifecycle.

## Files
```
kiyovu-api/app/Http/Controllers/Api/UserController.php
kiyovu-api/app/Http/Controllers/Api/RoleController.php
kiyovu-api/app/Http/Resources/UserResource.php
kiyovu-api/routes/api.php                    (updated)
kiyovu-web/src/hooks/useRoleOptions.ts
kiyovu-web/src/pages/admin/UsersPage.tsx
kiyovu-web/src/App.tsx                       (updated)
```

## Setup
No new migrations or seeders — this uses the `users`, `roles`, and
`lu_statuses`/`lu_languages` tables that already exist from Phase 1.
Just drop the files in and clear route cache:
```bash
php artisan route:clear
```
