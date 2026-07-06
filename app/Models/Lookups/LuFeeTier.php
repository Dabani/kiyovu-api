<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuFeeTier extends Model
{
    use IsLookup;

    protected $table = 'lu_fee_tiers';

    protected $fillable = [
        'code', 'label_en', 'label_fr', 'label_rw', 'min_monthly_rwf',
        'max_monthly_rwf', 'amenities_en', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'min_monthly_rwf' => 'integer',
            'max_monthly_rwf' => 'integer',
        ];
    }
}
