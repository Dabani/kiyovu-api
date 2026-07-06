<?php

namespace App\Models\Elections;

use App\Models\Lookups\LuElectedPosition;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectionHandoverReport extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'position_id', 'outgoing_official_name', 'incoming_official_name', 'handover_date',
        'outstanding_matters', 'key_contacts', 'pending_decisions',
        'access_and_assets_transferred', 'outgoing_signed', 'incoming_signed',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'handover_date' => 'date',
            'access_and_assets_transferred' => 'boolean',
            'outgoing_signed' => 'boolean',
            'incoming_signed' => 'boolean',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(LuElectedPosition::class, 'position_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
