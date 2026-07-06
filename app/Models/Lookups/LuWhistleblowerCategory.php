<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuWhistleblowerCategory extends Model
{
    use IsLookup;

    protected $table = 'lu_whistleblower_categories';
}
