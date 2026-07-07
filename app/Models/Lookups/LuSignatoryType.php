<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuSignatoryType extends Model
{
    use IsLookup;

    protected $table = 'lu_signatory_types';
}
