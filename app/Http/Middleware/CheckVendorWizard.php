<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckVendorWizard
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->user_type === 'vendor') {
            $vendorProfile = $user->vendorProfile;

            if (!$vendorProfile || $vendorProfile->step_completed < 7) {
                // Calculate next step
                $nextStep = ($vendorProfile->step_completed ?? 0) + 1;

                return redirect()->route('vendor.onboarding.step' . $nextStep)
                    ->with('error', 'Please complete your onboarding wizard before accessing dashboard.');
            }

            if ($user->status === 'pending') {
                return redirect()->route('vendor.pending')
                    ->with('info', 'Your account is under review.');
            }
        }

        return $next($request);
    }
}
