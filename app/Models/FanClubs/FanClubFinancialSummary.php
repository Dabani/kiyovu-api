<?php

namespace App\Models\FanClubs;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FanClubFinancialSummary extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'fan_club_id', 'report_year', 'total_income_rwf', 'total_expenses_rwf',
        'closing_balance_rwf', 'submitted_on', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_on' => 'date',
            'total_income_rwf' => 'integer',
            'total_expenses_rwf' => 'integer',
            'closing_balance_rwf' => 'integer',
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
