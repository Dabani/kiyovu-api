<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuElectedPosition extends Model
{
    use IsLookup;

    protected $table = 'lu_elected_positions';

    protected $fillable = [
        'code', 'label_en', 'label_fr', 'label_rw', 'term_years',
        'requires_criminal_record_certificate', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'term_years' => 'integer',
            'requires_criminal_record_certificate' => 'boolean',
        ];
    }
}
