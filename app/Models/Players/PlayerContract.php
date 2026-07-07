<?php

namespace App\Models\Players;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlayerContract extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'player_id', 'term_start', 'term_end', 'base_salary_rwf', 'bonuses_notes', 'benefits_notes',
        'player_obligations', 'organisation_obligations', 'termination_grounds',
        'dispute_resolution_mechanism', 'ceo_signed_on', 'sporting_director_signed_on',
        'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'term_start' => 'date',
            'term_end' => 'date',
            'ceo_signed_on' => 'date',
            'sporting_director_signed_on' => 'date',
            'base_salary_rwf' => 'integer',
        ];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }
}
