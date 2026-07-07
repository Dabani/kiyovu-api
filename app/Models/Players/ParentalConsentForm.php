<?php

namespace App\Models\Players;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentalConsentForm extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'player_id', 'guardian_name', 'relationship_to_minor', 'guardian_phone', 'consent_date',
        'activities_covered', 'medical_treatment_consent', 'media_image_consent',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'consent_date' => 'date',
            'medical_treatment_consent' => 'boolean',
            'media_image_consent' => 'boolean',
        ];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
