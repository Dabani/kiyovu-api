<?php

namespace App\Models\Elections;

use App\Models\Lookups\LuDisputeGround;
use App\Models\Lookups\LuElectedPosition;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectionDispute extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'position_id', 'election_cycle_year', 'dispute_ground_id', 'submitted_by_name',
        'submitted_on', 'grounds_detail', 'referred_to_cro', 'determination',
        'determination_date', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_on' => 'date',
            'determination_date' => 'date',
            'referred_to_cro' => 'boolean',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(LuElectedPosition::class, 'position_id');
    }

    public function disputeGround(): BelongsTo
    {
        return $this->belongsTo(LuDisputeGround::class, 'dispute_ground_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
