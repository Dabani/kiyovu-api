<?php

namespace App\Models\Hr;

use App\Models\Lookups\LuHqPosition;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecruitmentCandidate extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'vacancy_title', 'position_id', 'full_name', 'phone', 'email',
        'application_date', 'vacancy_published_on', 'vacancy_closing_date',
        'shortlisted', 'shortlist_score', 'shortlisting_notes', 'shortlisted_on',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
            'vacancy_published_on' => 'date',
            'vacancy_closing_date' => 'date',
            'shortlisted_on' => 'date',
            'shortlisted' => 'boolean',
            'shortlist_score' => 'decimal:2',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(LuHqPosition::class, 'position_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }

    public function interviewScores(): HasMany
    {
        return $this->hasMany(HrInterviewScore::class, 'candidate_id');
    }

    public function backgroundChecks(): HasMany
    {
        return $this->hasMany(HrBackgroundCheck::class, 'candidate_id');
    }
}
