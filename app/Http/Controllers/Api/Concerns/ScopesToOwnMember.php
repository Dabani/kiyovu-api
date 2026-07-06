<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Models\Membership\Member;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ScopesToOwnMember
{
    private const FULL_ACCESS_ROLES = ['super_admin', 'president', 'secretary_general', 'hr_committee'];

    /**
     * Applied in baseQuery(): a self-service `member` only sees rows tied to
     * their own members.id (via the request/resignation/etc. table's
     * `member_id` FK). Governance roles pass through unrestricted.
     */
    protected function scopeToOwnMember(Builder $query, Request $request): Builder
    {
        $user = $request->user();

        if ($user->hasAnyRole(self::FULL_ACCESS_ROLES)) {
            return $query;
        }

        if ($user->hasRole('member')) {
            $ownMemberId = Member::where('user_id', $user->id)->value('id');

            return $query->where('member_id', $ownMemberId ?? 0);
        }

        return $query;
    }
}
