<?php

namespace App\Models\FanClubs;

use App\Models\Lookups\LuFanSanction;
use App\Models\Lookups\LuIncidentType;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FanIncidentReport extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'fan_club_id', 'incident_type_id', 'incident_date', 'description', 'documented_on',
        'slo_report_due_on', 'slo_investigation_report', 'slo_report_submitted_on',
        'adjudicated_by_fan_discipline_commission', 'sanction_id', 'referred_to_law_enforcement',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'documented_on' => 'date',
            'slo_report_due_on' => 'date',
            'slo_report_submitted_on' => 'date',
            'adjudicated_by_fan_discipline_commission' => 'boolean',
            'referred_to_law_enforcement' => 'boolean',
        ];
    }

    public function fanClub(): BelongsTo
    {
        return $this->belongsTo(FanClub::class);
    }

    public function incidentType(): BelongsTo
    {
        return $this->belongsTo(LuIncidentType::class, 'incident_type_id');
    }

    public function sanction(): BelongsTo
    {
        return $this->belongsTo(LuFanSanction::class, 'sanction_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
