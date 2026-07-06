<?php

namespace App\Models\Hr;

use App\Models\Lookups\LuHqPosition;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrAppointmentRecommendation extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'vacancy_title', 'position_id', 'recommended_candidate_id', 'ranking_notes',
        'submitted_on', 'status_id', 'executive_organ_decision_date',
        'board_approval_required', 'board_approved', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_on' => 'date',
            'executive_organ_decision_date' => 'date',
            'board_approval_required' => 'boolean',
            'board_approved' => 'boolean',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(LuHqPosition::class, 'position_id');
    }

    public function recommendedCandidate(): BelongsTo
    {
        return $this->belongsTo(RecruitmentCandidate::class, 'recommended_candidate_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
