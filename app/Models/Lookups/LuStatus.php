<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuStatus extends Model
{
    use IsLookup;

    protected $table = 'lu_statuses';

    protected $fillable = [
        'code', 'label_en', 'label_fr', 'label_rw', 'applies_to',
        'color_hex', 'is_active', 'sort_order',
    ];

    public function scopeAppliesTo($query, string $module)
    {
        return $query->where('applies_to', $module);
    }
}
