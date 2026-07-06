<?php

namespace App\Models\Hr;

use App\Models\Lookups\LuHqPosition;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrConflictOfInterestDeclaration extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'declarant_name', 'position_id', 'declaration_date', 'conflict_description',
        'recusal_required', 'reviewed_by_name', 'next_annual_update_due',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'declaration_date' => 'date',
            'next_annual_update_due' => 'date',
            'recusal_required' => 'boolean',
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
}
