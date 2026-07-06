<?php

namespace App\Models\Hr;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrInterviewScore extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'candidate_id', 'interview_date', 'technical_competence_score',
        'values_alignment_score', 'position_specific_score', 'interviewer_notes',
        'recommended_to_proceed', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'interview_date' => 'date',
            'technical_competence_score' => 'decimal:1',
            'values_alignment_score' => 'decimal:1',
            'position_specific_score' => 'decimal:1',
            'recommended_to_proceed' => 'boolean',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(RecruitmentCandidate::class, 'candidate_id');
    }

    public function getTotalScoreAttribute(): float
    {
        return (float) $this->technical_competence_score
            + (float) $this->values_alignment_score
            + (float) $this->position_specific_score;
    }
}
