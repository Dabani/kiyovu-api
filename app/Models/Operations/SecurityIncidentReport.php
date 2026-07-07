<?php

namespace App\Models\Operations;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecurityIncidentReport extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'incident_date', 'event_description', 'incident_description', 'reported_by_name',
        'reported_on', 'coordinated_with_law_enforcement', 'coordinated_with_stadium_authorities',
        'action_taken', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'reported_on' => 'date',
            'coordinated_with_law_enforcement' => 'boolean',
            'coordinated_with_stadium_authorities' => 'boolean',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
