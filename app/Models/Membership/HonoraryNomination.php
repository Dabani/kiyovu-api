<?php

namespace App\Models\Membership;

use App\Models\Lookups\LuNomineeType;
use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HonoraryNomination extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'nominee_name', 'nominee_type_id', 'basis_for_nomination',
        'executive_organ_endorsed', 'board_endorsed', 'nominated_on',
        'status_id', 'ga_decision_date', 'conflict_of_interest_disclosed',
        'conflict_of_interest_notes', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'nominated_on' => 'date',
            'ga_decision_date' => 'date',
            'executive_organ_endorsed' => 'boolean',
            'board_endorsed' => 'boolean',
            'conflict_of_interest_disclosed' => 'boolean',
        ];
    }

    public function nomineeType(): BelongsTo
    {
        return $this->belongsTo(LuNomineeType::class, 'nominee_type_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }

    public function dossier(): HasOne
    {
        return $this->hasOne(HonoraryNominationDossier::class);
    }

    /** True once both organs have endorsed (required before GA submission — Art. 20). */
    public function getFullyEndorsedAttribute(): bool
    {
        return $this->executive_organ_endorsed && $this->board_endorsed;
    }
}
