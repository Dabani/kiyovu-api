<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuAssetCategory extends Model
{
    use IsLookup;

    protected $table = 'lu_asset_categories';
}
