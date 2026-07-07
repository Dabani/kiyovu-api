<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\BaseModuleController;
use App\Http\Resources\UserResource;
use App\Models\Lookups\LuStatus;
use App\Models\User;
use App\Notifications\AdminNewRegistrationNotification;
use App\Notifications\WelcomeRegistrationNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Platform user management — distinct from the 53 IRR-derived module
 * forms. This is core account/role administration: who can log in, what
 * status they're in, and which of the 22 seeded roles they hold.
 */
class UserController extends BaseModuleController
{
    protected function modelClass(): string
    {
        return User::class;
    }

    protected function resourceClass(): string
    {
        return UserResource::class;
    }

    protected function searchableColumns(): array
    {
        return ['first_name', 'last_name', 'email', 'phone'];
    }

    protected function filterableColumns(): array
    {
        return ['status_id'];
    }

    protected function withRelations(): array
    {
        return ['status', 'preferredLanguage'];
    }

    protected function deletePermission(): ?string
    {
        return 'users.manage';
    }

    protected function baseQuery(Request $request): Builder
    {
        return User::query()->with('roles');
    }

    public function index(Request $request)
    {
        Gate::authorize('users.manage');

        return parent::index($request);
    }

    public function show(int $id)
    {
        Gate::authorize('users.manage');

        $user = User::with(['status', 'preferredLanguage', 'roles'])->findOrFail($id);

        return new UserResource($user);
    }

    public function store(Request $request)
    {
        Gate::authorize('users.manage');

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'national_id' => ['nullable', 'string', 'max:50', 'unique:users,national_id'],
            'preferred_language_id' => ['nullable', 'exists:lu_languages,id'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        // Admin-created accounts get a random temporary password — there is
        // no self-service password reset flow yet (see README known gaps),
        // so it's returned once in this response for the admin to relay.
        $temporaryPassword = Str::password(12);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'national_id' => $data['national_id'] ?? null,
            'preferred_language_id' => $data['preferred_language_id'] ?? null,
            'status_id' => $data['status_id'],
            'password' => Hash::make($temporaryPassword),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $user->syncRoles($data['roles']);

        // Notify the new user and every super_admin — both notification
        // classes already existed from Phase 1 but were never triggered
        // anywhere until now.
        $user->notify(new WelcomeRegistrationNotification($user));
        foreach (User::role('super_admin')->where('id', '!=', $user->id)->get() as $admin) {
            $admin->notify(new AdminNewRegistrationNotification($user));
        }

        return (new UserResource($user->load(array_merge($this->withRelations(), ['roles']))))
            ->additional(['temporary_password' => $temporaryPassword]);
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('users.manage');

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'phone' => ['nullable', 'string', 'max:30'],
            'national_id' => ['nullable', 'string', 'max:50', "unique:users,national_id,{$user->id}"],
            'preferred_language_id' => ['nullable', 'exists:lu_languages,id'],
            'status_id' => ['required', 'exists:lu_statuses,id'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'national_id' => $data['national_id'] ?? null,
            'preferred_language_id' => $data['preferred_language_id'] ?? null,
            'status_id' => $data['status_id'],
            'updated_by' => Auth::id(),
        ]);

        $user->syncRoles($data['roles']);

        return new UserResource($user->load(array_merge($this->withRelations(), ['roles'])));
    }

    /** POST /api/users/{user}/reset-password — issues a new temporary password. */
    public function resetPassword(User $user)
    {
        Gate::authorize('users.manage');

        $temporaryPassword = Str::password(12);
        $user->forceFill(['password' => Hash::make($temporaryPassword), 'updated_by' => Auth::id()])->save();

        return response()->json(['temporary_password' => $temporaryPassword]);
    }
}
