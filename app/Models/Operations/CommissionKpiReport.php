<?php

namespace App\Models\Operations;

use App\Models\Lookups\LuCommissionPillar;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionKpiReport extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'pillar_id', 'plan_year', 'kpis_established', 'established_on',
        'mid_year_review_notes', 'mid_year_reviewed_on', 'year_end_review_notes',
        'year_end_reviewed_on', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'established_on' => 'date',
            'mid_year_reviewed_on' => 'date',
            'year_end_reviewed_on' => 'date',
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
