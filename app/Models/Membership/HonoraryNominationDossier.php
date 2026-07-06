<?php

namespace App\Models\Membership;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HonoraryNominationDossier extends Model
{
    use Auditable, SoftDeletes;

    protected $fillable = [
        'honorary_nomination_id', 'contributions_summary', 'justification',
        'prepared_on', 'prepared_by_name', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return ['prepared_on' => 'date'];
    }

    public function nomination(): BelongsTo
    {
        return $this->belongsTo(HonoraryNomination::class, 'honorary_nomination_id');
    }
}
