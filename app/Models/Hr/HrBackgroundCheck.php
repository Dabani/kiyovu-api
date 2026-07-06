<?php

namespace App\Models\Hr;

use App\Models\Lookups\LuHqPosition;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrBackgroundCheck extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'candidate_id', 'subject_name', 'position_id', 'role_involves_minors',
        'consent_given_on', 'verification_notes', 'outcome_status_id',
        'cleared_by_name', 'cleared_on', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'consent_given_on' => 'date',
            'cleared_on' => 'date',
            'role_involves_minors' => 'boolean',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(RecruitmentCandidate::class, 'candidate_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(LuHqPosition::class, 'position_id');
    }

    public function outcomeStatus(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'outcome_status_id');
    }
}
