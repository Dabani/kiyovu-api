<?php

namespace App\Models\FanClubs;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FanClubAnnualReport extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'fan_club_id', 'report_year', 'activities_summary', 'membership_highlights',
        'financial_highlights', 'submitted_on', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return ['submitted_on' => 'date'];
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
