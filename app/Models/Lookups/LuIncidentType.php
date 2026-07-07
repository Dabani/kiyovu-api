<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuIncidentType extends Model
{
    use IsLookup;

    protected $table = 'lu_incident_types';
}
