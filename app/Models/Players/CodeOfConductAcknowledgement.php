<?php

namespace App\Models\Players;

use App\Models\Lookups\LuHqPosition;
use App\Models\Lookups\LuSignatoryType;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodeOfConductAcknowledgement extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'signatory_name', 'signatory_type_id', 'position_id', 'signed_date',
        'safeguarding_training_completed_on', 'safeguarding_certification_expiry',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'signed_date' => 'date',
            'safeguarding_training_completed_on' => 'date',
            'safeguarding_certification_expiry' => 'date',
        ];
    }

    public function signatoryType(): BelongsTo
    {
        return $this->belongsTo(LuSignatoryType::class, 'signatory_type_id');
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
