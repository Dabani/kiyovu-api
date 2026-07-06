<?php

namespace App\Models\Financial;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PettyCashVoucher extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'description', 'amount_rwf', 'department', 'requested_by_name', 'departmental_head_name',
        'voucher_date', 'receipt_attached', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'voucher_date' => 'date',
            'amount_rwf' => 'integer',
            'receipt_attached' => 'boolean',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
