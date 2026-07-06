<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuExpenditureTier extends Model
{
    use IsLookup;

    protected $table = 'lu_expenditure_tiers';

    protected $fillable = [
        'code', 'label_en', 'label_fr', 'label_rw', 'min_amount_rwf',
        'max_amount_rwf', 'required_authoriser_en', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'min_amount_rwf' => 'integer',
            'max_amount_rwf' => 'integer',
        ];
    }
}
