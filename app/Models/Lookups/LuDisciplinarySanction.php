<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuDisciplinarySanction extends Model
{
    use IsLookup;

    protected $table = 'lu_disciplinary_sanctions';
}
