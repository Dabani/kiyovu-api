<?php

namespace App\Models\Elections;

use App\Models\Lookups\LuElectedPosition;
use App\Models\Lookups\LuStatus;
use App\Models\Membership\Member;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectionNomination extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'election_cycle_year', 'position_id', 'member_id', 'candidate_full_name',
        'statement_of_intent', 'eligibility_declaration_signed',
        'no_disqualifying_convictions_declared', 'legal_representative_limit_confirmed',
        'criminal_record_certificate_date', 'nominated_on', 'eligibility_determined_on',
        'eligibility_approved', 'eligibility_notes', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'criminal_record_certificate_date' => 'date',
            'nominated_on' => 'date',
            'eligibility_determined_on' => 'date',
            'eligibility_declaration_signed' => 'boolean',
            'no_disqualifying_convictions_declared' => 'boolean',
            'legal_representative_limit_confirmed' => 'boolean',
            'eligibility_approved' => 'boolean',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(LuElectedPosition::class, 'position_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }

    public function tallySheets(): HasMany
    {
        return $this->hasMany(ElectionTallySheet::class, 'nomination_id');
    }
}
