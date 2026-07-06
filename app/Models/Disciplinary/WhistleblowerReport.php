<?php

namespace App\Models\Disciplinary;

use App\Models\Lookups\LuStatus;
use App\Models\Lookups\LuWhistleblowerCategory;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhistleblowerReport extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'category_id', 'is_anonymous', 'reporter_name', 'reported_on', 'description',
        'receipt_acknowledged_on', 'initial_assessment_completed_on', 'referred_to',
        'retaliation_protection_confirmed', 'related_disciplinary_case_id', 'status_id',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'reported_on' => 'date',
            'receipt_acknowledged_on' => 'date',
            'initial_assessment_completed_on' => 'date',
            'is_anonymous' => 'boolean',
            'retaliation_protection_confirmed' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(LuWhistleblowerCategory::class, 'category_id');
    }

    public function relatedCase(): BelongsTo
    {
        return $this->belongsTo(DisciplinaryCase::class, 'related_disciplinary_case_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
