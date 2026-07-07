<?php

namespace App\Models\Operations;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestRegister extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'match_date', 'event_description', 'guest_name', 'guest_organization', 'is_partner_guest',
        'host_name', 'ceo_approved_on', 'guest_signed', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'match_date' => 'date',
            'ceo_approved_on' => 'date',
            'is_partner_guest' => 'boolean',
            'guest_signed' => 'boolean',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
