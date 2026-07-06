<?php

namespace App\Models\Lookups;

use App\Traits\IsLookup;
use Illuminate\Database\Eloquent\Model;

class LuPaymentMethod extends Model
{
    use IsLookup;

    protected $table = 'lu_payment_methods';
}
