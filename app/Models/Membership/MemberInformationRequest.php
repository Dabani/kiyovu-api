<?php

namespace App\Models\Membership;

use App\Models\Lookups\LuStatus;
use App\Models\User;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberInformationRequest extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'member_id', 'information_requested', 'requested_on', 'status_id',
        'responded_on', 'response_notes', 'denial_reason', 'appealed_to_board',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'requested_on' => 'date',
            'responded_on' => 'date',
            'appealed_to_board' => 'boolean',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
