<?php

namespace App\Models\Financial;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetHandover extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'asset_id', 'outgoing_custodian_name', 'incoming_custodian_name', 'handover_date',
        'condition_notes', 'outgoing_signed', 'incoming_signed', 'status_id',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'handover_date' => 'date',
            'outgoing_signed' => 'boolean',
            'incoming_signed' => 'boolean',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(AssetRegisterEntry::class, 'asset_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
