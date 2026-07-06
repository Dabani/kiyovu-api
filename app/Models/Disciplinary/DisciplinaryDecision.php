<?php

namespace App\Models\Disciplinary;

use App\Models\Lookups\LuDisciplinarySanction;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisciplinaryDecision extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'case_id', 'decision_date', 'case_summary', 'findings_of_fact', 'rules_violated',
        'reasoning', 'sanction_id', 'sanction_effective_date', 'appeal_deadline',
        'communicated_to_respondent', 'communicated_to_executive_organ',
        'recorded_by_secretary_general', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'decision_date' => 'date',
            'sanction_effective_date' => 'date',
            'appeal_deadline' => 'date',
            'communicated_to_respondent' => 'boolean',
            'communicated_to_executive_organ' => 'boolean',
            'recorded_by_secretary_general' => 'boolean',
        ];
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(DisciplinaryCase::class, 'case_id');
    }

    public function sanction(): BelongsTo
    {
        return $this->belongsTo(LuDisciplinarySanction::class, 'sanction_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
