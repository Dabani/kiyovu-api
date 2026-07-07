<?php

namespace App\Models\FanClubs;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FanClubPaymentConfirmation extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'fan_club_id', 'contribution_month', 'amount_rwf', 'payment_reference',
        'submitted_on', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'contribution_month' => 'date',
            'submitted_on' => 'date',
            'amount_rwf' => 'integer',
        ];
    }

    public function fanClub(): BelongsTo
    {
        return $this->belongsTo(FanClub::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
