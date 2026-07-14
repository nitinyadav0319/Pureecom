<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth()->user();

        // Check if user is logged in and has permission
        if (!$user || !$user->can($permission)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
