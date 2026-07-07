<?php

namespace App\Models\FanClubs;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FanClubMembershipRegister extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'fan_club_id', 'quarter', 'register_year', 'active_member_count', 'submitted_on',
        'audited', 'audited_on', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_on' => 'date',
            'audited_on' => 'date',
            'audited' => 'boolean',
            'active_member_count' => 'integer',
        ];
    }

    public function fanClub(): BelongsTo
    {
        return $this->belongsTo(FanClub::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }

    /**
     * Art. 183 — monthly contribution formula, computed from the most
     * recent register: RWF 1,000/member if ≥50 active members, else a flat
     * RWF 50,000 minimum regardless of count.
     */
    public function getExpectedMonthlyContributionAttribute(): int
    {
        return $this->active_member_count >= 50
            ? $this->active_member_count * 1000
            : 50000;
    }
}
