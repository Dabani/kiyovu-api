<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuCommissionPillar extends Model
{
    use IsLookup;

    protected $table = 'lu_commission_pillars';
}
