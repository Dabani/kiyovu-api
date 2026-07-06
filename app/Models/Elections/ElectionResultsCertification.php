<?php

namespace App\Models\Elections;

use App\Models\Lookups\LuElectedPosition;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectionResultsCertification extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'election_cycle_year', 'position_id', 'winning_nomination_id', 'was_tie_broken_by_lots',
        'certified_on', 'commission_member_1_name', 'commission_member_2_name',
        'commission_member_3_name', 'filed_with_secretary_general', 'handover_date',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'certified_on' => 'date',
            'handover_date' => 'date',
            'was_tie_broken_by_lots' => 'boolean',
            'filed_with_secretary_general' => 'boolean',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(LuElectedPosition::class, 'position_id');
    }

    public function winningNomination(): BelongsTo
    {
        return $this->belongsTo(ElectionNomination::class, 'winning_nomination_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
