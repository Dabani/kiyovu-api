<?php

namespace App\Models\Players;

use App\Models\Lookups\LuLoanDirection;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerLoanAgreement extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'player_id', 'direction_id', 'counterparty_club_name', 'start_date', 'end_date',
        'compensation_rwf', 'obligations_notes', 'recall_provisions', 'executive_organ_approved',
        'board_notified', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'compensation_rwf' => 'integer',
            'executive_organ_approved' => 'boolean',
            'board_notified' => 'boolean',
        ];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function direction(): BelongsTo
    {
        return $this->belongsTo(LuLoanDirection::class, 'direction_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
