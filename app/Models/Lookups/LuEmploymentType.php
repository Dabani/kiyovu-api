<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuEmploymentType extends Model
{
    use IsLookup;

    protected $table = 'lu_employment_types';
}
