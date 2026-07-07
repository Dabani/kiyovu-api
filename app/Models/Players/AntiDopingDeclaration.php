<?php

namespace App\Models\Players;

use App\Models\Lookups\LuStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AntiDopingDeclaration extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'player_id', 'declaration_date', 'wada_list_acknowledged', 'tue_application_filed',
        'tue_notes', 'status_id', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'declaration_date' => 'date',
            'wada_list_acknowledged' => 'boolean',
            'tue_application_filed' => 'boolean',
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
