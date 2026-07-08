<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hr\HrEmploymentContract;
use App\Models\Lookups\LuStatus;
use App\Models\Membership\Member;
use App\Models\User;
use App\Notifications\AdminNewRegistrationNotification;
use App\Notifications\WelcomeRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Deliberately kept separate from MemberController / HrEmploymentContractController
 * so this feature doesn't require touching (or risk regressing) any of the
 * 53 module-bundle controllers. Each module's own "Create Login Account"
 * button just calls this one shared endpoint.
 *
 * Extending to a new source type (e.g. a future commission-membership
 * table) is one entry in SOURCES — no changes needed elsewhere.
 */
class AccountProvisioningController extends Controller
{
    private const SOURCES = [
        'member' => [
            'model' => Member::class,
            'name_field' => 'full_name',
            'email_field' => 'email',
            'default_roles' => ['member'],
        ],
        'hr-employment-contract' => [
            'model' => HrEmploymentContract::class,
            'name_field' => 'employee_full_name',
            'email_field' => null, // staff records don't capture email — admin supplies it
            'default_roles' => [], // varies by position (hq_business vs hq_sport) — admin picks
        ],
    ];

    /**
     * Committees/commissions are intentionally not in SOURCES yet — Bundle 8
     * modeled commission *pillars* and their work plans/KPIs, not individual
     * commission membership, so there's no source table to provision from.
     * GET here returns which source types are actually available so the
     * frontend can hide the button rather than guess.
     */
    public function availableSources()
    {
        Gate::authorize('users.manage');

        return response()->json(array_keys(self::SOURCES));
    }

    public function store(Request $request, string $sourceType, int $sourceId)
    {
        Gate::authorize('users.manage');

        if (! isset(self::SOURCES[$sourceType])) {
            return response()->json([
                'message' => "Account provisioning isn't available for '{$sourceType}' yet.",
            ], 404);
        }

        $config = self::SOURCES[$sourceType];
        $source = $config['model']::findOrFail($sourceId);

        $data = $request->validate([
            'email' => ['required', 'email'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        return DB::transaction(function () use ($source, $config, $data) {
            $fullName = $source->{$config['name_field']};
            [$firstName, $lastName] = $this->splitName($fullName);

            $existing = User::where('email', $data['email'])->first();
            $isNewAccount = ! $existing;
            $temporaryPassword = null;

            if ($existing) {
                $user = $existing;
            } else {
                $activeStatus = LuStatus::where('code', 'active')->first();
                $temporaryPassword = Str::password(12);

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $data['email'],
                    'status_id' => $activeStatus?->id,
                    'password' => Hash::make($temporaryPassword),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            $user->syncRoles(array_unique([...$user->getRoleNames()->toArray(), ...$data['roles']]));

            // Link the domain record back to the account, same pattern as
            // members.user_id already established in Bundle 1.
            if ($source->isFillable('user_id')) {
                $source->forceFill(['user_id' => $user->id])->save();
            }

            if ($isNewAccount) {
                $user->notify(new WelcomeRegistrationNotification($user));
                foreach (User::role('super_admin')->get() as $admin) {
                    $admin->notify(new AdminNewRegistrationNotification($user));
                }
            }

            return response()->json([
                'user_id' => $user->id,
                'email' => $user->email,
                'is_new_account' => $isNewAccount,
                'temporary_password' => $temporaryPassword, // null when reusing an existing account
            ]);
        });
    }

    private function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);

        return [$parts[0] ?? $fullName, $parts[1] ?? ''];
    }
}
