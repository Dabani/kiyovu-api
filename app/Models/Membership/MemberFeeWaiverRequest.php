<?php

namespace App\Models\Membership;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberFeeWaiverRequest extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'member_id', 'requested_on', 'hardship_justification', 'status_id',
        'reviewed_on', 'valid_until', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'requested_on' => 'date',
            'reviewed_on' => 'date',
            'valid_until' => 'date',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
