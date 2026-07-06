<?php

namespace App\Models\Financial;

use App\Models\Lookups\LuExpenditureTier;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentAuthorization extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'description', 'amount_rwf', 'expenditure_tier_id', 'payee_name', 'payment_date',
        'authorized_by_ceo_name', 'co_signed_by_treasurer_name', 'executive_organ_resolution',
        'ga_resolution', 'supporting_documentation_ref', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount_rwf' => 'integer',
            'executive_organ_resolution' => 'boolean',
            'ga_resolution' => 'boolean',
        ];
    }

    public function expenditureTier(): BelongsTo
    {
        return $this->belongsTo(LuExpenditureTier::class, 'expenditure_tier_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
