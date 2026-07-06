<?php

namespace App\Models\Membership;

use App\Models\Lookups\LuStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class MemberInactiveStatusRequest extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'member_id', 'requested_on', 'reason', 'effective_from', 'max_end_date',
        'reverted_to_active_on', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'requested_on' => 'date',
            'effective_from' => 'date',
            'max_end_date' => 'date',
            'reverted_to_active_on' => 'date',
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
