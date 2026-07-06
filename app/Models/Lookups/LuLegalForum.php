<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuLegalForum extends Model
{
    use IsLookup;

    protected $table = 'lu_legal_forums';
}
