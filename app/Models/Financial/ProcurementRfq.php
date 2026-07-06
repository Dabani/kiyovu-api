<?php

namespace App\Models\Financial;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcurementRfq extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'item_description', 'estimated_value_rwf', 'quotations_received', 'evaluation_notes',
        'selected_vendor_name', 'rfq_date', 'award_date', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'rfq_date' => 'date',
            'award_date' => 'date',
            'estimated_value_rwf' => 'integer',
            'quotations_received' => 'integer',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
