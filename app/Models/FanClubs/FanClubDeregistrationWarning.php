<?php

namespace App\Models\FanClubs;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FanClubDeregistrationWarning extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'fan_club_id', 'grounds', 'issued_on', 'remedy_deadline', 'remedied',
        'explanation_invited_on', 'explanation_received', 'executive_organ_decision_date',
        'deregistration_decided', 'decision_reasons', 'appealed_to_ga', 'appeal_filed_on',
        'ga_appeal_upheld', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'issued_on' => 'date',
            'remedy_deadline' => 'date',
            'explanation_invited_on' => 'date',
            'executive_organ_decision_date' => 'date',
            'appeal_filed_on' => 'date',
            'remedied' => 'boolean',
            'deregistration_decided' => 'boolean',
            'appealed_to_ga' => 'boolean',
            'ga_appeal_upheld' => 'boolean',
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
