<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleScope
{
    /**
     * Usage in routes: ->middleware('role.scope:commission_president,commission,commission')
     * The third arg is the route parameter name whose value must match the
     * user's role_scopes.scope_id for that scope_type. Users with a global
     * (unscoped) assignment of the role pass through automatically.
     */
    public function handle(Request $request, Closure $next, string $role, string $scopeType, string $routeParam): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole($role)) {
            return $next($request); // not this role, other guards apply
        }

        $hasAnyScope = $user->roleScopes()
            ->where('role_name', $role)
            ->where('scope_type', $scopeType)
            ->exists();

        // Unscoped assignment of this role = full access to the module.
        if (! $hasAnyScope) {
            return $next($request);
        }

        $routeValue = (int) $request->route($routeParam);

        if (! $user->isScopedTo($role, $scopeType, $routeValue)) {
            abort(403, 'You do not have access to this record.');
        }

        return $next($request);
    }
}
