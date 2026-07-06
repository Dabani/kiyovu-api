<?php

namespace App\Models\Hr;

use App\Models\Lookups\LuEmploymentType;
use App\Models\Lookups\LuHqPosition;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrEmploymentContract extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'candidate_id', 'employee_full_name', 'position_id', 'employment_type_id',
        'duties_and_kpis', 'qualifications_required', 'reporting_line',
        'remuneration_rwf_monthly', 'working_hours', 'term_start', 'term_end',
        'termination_grounds', 'confidentiality_acknowledged', 'ceo_signed_on',
        'appointee_signed_on', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'term_start' => 'date',
            'term_end' => 'date',
            'ceo_signed_on' => 'date',
            'appointee_signed_on' => 'date',
            'confidentiality_acknowledged' => 'boolean',
            'remuneration_rwf_monthly' => 'integer',
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

    public function employmentType(): BelongsTo
    {
        return $this->belongsTo(LuEmploymentType::class, 'employment_type_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
