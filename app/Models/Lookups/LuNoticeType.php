<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuNoticeType extends Model
{
    use IsLookup;

    protected $table = 'lu_notice_types';

    protected $fillable = ['code', 'label_en', 'label_fr', 'label_rw', 'minimum_notice_days', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'minimum_notice_days' => 'integer',
        ];
    }
}
