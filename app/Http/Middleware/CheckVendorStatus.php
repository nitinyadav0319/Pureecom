<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckVendorStatus
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'vendor') {
            $profile = $user->vendorProfile;

            // agar profile hi nahi hai → wizard
            if (!$profile) {
                return redirect()->route('vendor.wizard');
            }

            // agar profile hai par wizard complete nahi
            if (!$profile->wizard_completed) {
                return redirect()->route('vendor.wizard');
            }

            // agar wizard complete hai but approval pending
            if ($profile->wizard_completed && !$profile->is_approved) {
                return redirect()->route('vendor.pending');
            }
        }

        return $next($request);
    }
}
