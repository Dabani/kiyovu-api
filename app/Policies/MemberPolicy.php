<?php

namespace App\Policies;

use App\Models\Membership\Member;
use App\Models\User;

class MemberPolicy
{
    private const FULL_ACCESS_ROLES = ['super_admin', 'president', 'secretary_general'];

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([...self::FULL_ACCESS_ROLES, 'member', 'honorary_member']);
    }

    public function view(User $user, Member $member): bool
    {
        if ($user->hasAnyRole(self::FULL_ACCESS_ROLES)) {
            return true;
        }

        // Self-service: a `member` role may only view their own registry entry.
        return $member->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(self::FULL_ACCESS_ROLES);
    }

    public function update(User $user, Member $member): bool
    {
        return $user->hasAnyRole(self::FULL_ACCESS_ROLES);
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->hasRole('super_admin');
    }
}
