<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuDisciplinaryCaseSource extends Model
{
    use IsLookup;

    protected $table = 'lu_disciplinary_case_sources';
}
