<?php

namespace App\Models\Players;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SafeguardingConcernReport extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'is_anonymous', 'reporter_name', 'concern_date', 'description', 'subject_reference',
        'receipt_acknowledged_on', 'initial_assessment_completed_on', 'risk_identified',
        'reported_to_authorities_on', 'accused_suspended_from_minors_contact',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'concern_date' => 'date',
            'receipt_acknowledged_on' => 'date',
            'initial_assessment_completed_on' => 'date',
            'reported_to_authorities_on' => 'date',
            'is_anonymous' => 'boolean',
            'risk_identified' => 'boolean',
            'accused_suspended_from_minors_contact' => 'boolean',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
