<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckVendor
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // agar login hi nahi hai
        if (!$user) {
            abort(403, 'Access denied. Please login first.');
        }
         if (strtolower($user->account_type) === 'customer' || strtolower($user->user_type) === 'admin') {
            return $next($request);
        }

        // check account_type ya user_type me vendor hai ya nahi
        if (
            strtolower($user->account_type) !== 'vendor' &&
            strtolower($user->user_type) !== 'vendor'
        ) {
            abort(403, 'Access denied. Only vendors can access this page.');
        }

        // ✅ status check
        if (strtolower($user->status) !== 'approved') {
            // pending, incomplete ya rejected ke liye redirect
            return redirect()->route('vendor.pending')
                ->with('info', 'Your vendor account is not approved yet.');
        }

        return $next($request);
    }
}
