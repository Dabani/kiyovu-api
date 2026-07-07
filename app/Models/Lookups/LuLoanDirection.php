<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuLoanDirection extends Model
{
    use IsLookup;

    protected $table = 'lu_loan_directions';
}
