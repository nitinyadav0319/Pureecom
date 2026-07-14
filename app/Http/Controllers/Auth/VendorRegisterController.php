<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorRegisterController extends Controller
{
    // Show vendor registration form
    public function showRegistrationForm()
    {
        return view('auth.vendor-register'); // yaha tumhara Blade file ka path
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => Hash::make($request->password),
            'account_type' => 'vendor',
             'user_type'    => 'vendor',
            'status'       => 'incomplete',
        ]);

        // Assign vendor role
        $user->assignRole('vendor');

        // Auto-login the newly registered vendor
        \Auth::login($user);

        // Redirect to first step of onboarding wizard
        return redirect()->route('vendor.onboarding.step1')
            ->with('success', 'Account created successfully. Please complete your vendor onboarding.');
    }
}
