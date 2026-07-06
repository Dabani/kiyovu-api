<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuMembershipCategory extends Model
{
    use IsLookup;

    protected $table = 'lu_membership_categories';

    protected $fillable = ['code', 'label_en', 'label_fr', 'label_rw', 'description_en', 'is_active', 'sort_order'];
}
