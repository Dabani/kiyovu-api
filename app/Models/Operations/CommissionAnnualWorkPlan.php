<?php

namespace App\Models\Operations;

use App\Models\Lookups\LuCommissionPillar;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionAnnualWorkPlan extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'pillar_id', 'plan_year', 'objectives', 'submitted_on',
        'executive_organ_approved_on', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_on' => 'date',
            'executive_organ_approved_on' => 'date',
        ];
    }

    public function pillar(): BelongsTo
    {
        return $this->belongsTo(LuCommissionPillar::class, 'pillar_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
