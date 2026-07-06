<?php

namespace App\Models\Financial;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcurementTender extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'item_description', 'estimated_value_rwf', 'tender_published_on', 'tender_closing_date',
        'evaluation_committee_names', 'awarded_vendor_name', 'award_date', 'awarded_value_rwf',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'tender_published_on' => 'date',
            'tender_closing_date' => 'date',
            'award_date' => 'date',
            'estimated_value_rwf' => 'integer',
            'awarded_value_rwf' => 'integer',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
