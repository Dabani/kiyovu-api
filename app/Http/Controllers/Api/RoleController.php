<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /** GET /api/roles — every seeded role, for the User Management role picker. */
    public function index()
    {
        Gate::authorize('users.manage');

        return Role::orderBy('name')->get(['id', 'name']);
    }
}
