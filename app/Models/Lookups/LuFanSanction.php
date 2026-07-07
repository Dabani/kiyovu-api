<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuFanSanction extends Model
{
    use IsLookup;

    protected $table = 'lu_fan_sanctions';
}
