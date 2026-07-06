<?php

namespace App\Models\Disciplinary;

use App\Models\Lookups\LuDisciplinaryCaseSource;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisciplinaryCase extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'case_source_id', 'respondent_name', 'complainant_name', 'incident_description',
        'initiated_on', 'receipt_acknowledged_on', 'preliminary_review_completed_on',
        'jurisdiction_confirmed', 'prima_facie_case', 'investigation_completed_on',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'initiated_on' => 'date',
            'receipt_acknowledged_on' => 'date',
            'preliminary_review_completed_on' => 'date',
            'investigation_completed_on' => 'date',
            'jurisdiction_confirmed' => 'boolean',
            'prima_facie_case' => 'boolean',
        ];
    }

    public function caseSource(): BelongsTo
    {
        return $this->belongsTo(LuDisciplinaryCaseSource::class, 'case_source_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }

    public function notices(): HasMany
    {
        return $this->hasMany(DisciplinaryNotice::class, 'case_id');
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(DisciplinaryDecision::class, 'case_id');
    }
}
