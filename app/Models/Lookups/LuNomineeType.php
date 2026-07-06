<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuNomineeType extends Model
{
    use IsLookup;

    protected $table = 'lu_nominee_types';
}
