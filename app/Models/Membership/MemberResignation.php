<?php

namespace App\Models\Membership;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberResignation extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'member_id', 'submitted_on', 'resignation_letter', 'outstanding_obligations',
        'outstanding_obligations_notes', 'status_id', 'ga_approval_date',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_on' => 'date',
            'ga_approval_date' => 'date',
            'outstanding_obligations' => 'boolean',
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
