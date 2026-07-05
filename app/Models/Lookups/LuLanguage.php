<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuLanguage extends Model
{
    use IsLookup;

    protected $table = 'lu_languages';
}
