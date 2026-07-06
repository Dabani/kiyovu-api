<?php

namespace App\Models\Financial;

use App\Models\Lookups\LuContractType;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WrittenContract extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'contract_type_id', 'counterparty_name', 'description', 'value_rwf', 'monthly_value_rwf',
        'start_date', 'end_date', 'executive_organ_approved', 'ga_notified',
        'ga_approval_required', 'ga_approved', 'signed_on', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'signed_on' => 'date',
            'value_rwf' => 'integer',
            'monthly_value_rwf' => 'integer',
            'executive_organ_approved' => 'boolean',
            'ga_notified' => 'boolean',
            'ga_approval_required' => 'boolean',
            'ga_approved' => 'boolean',
        ];
    }

    public function contractType(): BelongsTo
    {
        return $this->belongsTo(LuContractType::class, 'contract_type_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
