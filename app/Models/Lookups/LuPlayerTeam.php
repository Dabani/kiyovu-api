<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuPlayerTeam extends Model
{
    use IsLookup;

    protected $table = 'lu_player_teams';
}
