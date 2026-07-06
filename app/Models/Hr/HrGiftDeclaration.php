<?php

namespace App\Models\Hr;

use App\Models\Lookups\LuGiftDisposition;
use App\Models\Lookups\LuHqPosition;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrGiftDeclaration extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'declarant_name', 'position_id', 'gift_description', 'estimated_value_rwf',
        'date_received', 'declared_on', 'disposition_id', 'status_id',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date_received' => 'date',
            'declared_on' => 'date',
            'estimated_value_rwf' => 'integer',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(LuHqPosition::class, 'position_id');
    }

    public function disposition(): BelongsTo
    {
        return $this->belongsTo(LuGiftDisposition::class, 'disposition_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
