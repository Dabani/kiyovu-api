<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuHqPosition extends Model
{
    use IsLookup;

    protected $table = 'lu_hq_positions';

    protected $fillable = [
        'code', 'label_en', 'label_fr', 'label_rw', 'division',
        'involves_minors', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'involves_minors' => 'boolean',
        ];
    }
}
