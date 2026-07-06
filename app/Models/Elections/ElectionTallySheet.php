<?php

namespace App\Models\Elections;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElectionTallySheet extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'nomination_id', 'election_date', 'votes_received', 'invalid_ballots_count',
        'independent_observer_present', 'observer_names', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'election_date' => 'date',
            'votes_received' => 'integer',
            'invalid_ballots_count' => 'integer',
            'independent_observer_present' => 'boolean',
        ];
    }

    public function nomination(): BelongsTo
    {
        return $this->belongsTo(ElectionNomination::class, 'nomination_id');
    }
}
