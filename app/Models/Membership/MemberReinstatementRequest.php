<?php

namespace App\Models\Membership;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberReinstatementRequest extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'member_id', 'submitted_on', 'suspension_completed_on', 'compliance_evidence',
        'cro_recommendation', 'status_id', 'decided_on', 'ongoing_conditions',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_on' => 'date',
            'suspension_completed_on' => 'date',
            'decided_on' => 'date',
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
