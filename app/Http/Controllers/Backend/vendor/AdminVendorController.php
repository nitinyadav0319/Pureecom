<?php

namespace App\Http\Controllers\Backend\vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorProfile;
use Illuminate\Http\Request;

class AdminVendorController extends Controller
{
    // Show list of vendors with status pending
    public function index()
    {
        $vendors = VendorProfile::whereHas('user', function($q) {
            $q->where('status', 'pending'); // sirf pending wale hi dikhao
        })->with('user')->get();

        return view('auth.index', compact('vendors'));
    }

    // Approve vendor
    public function approve($id)
    {
        $vendor = VendorProfile::findOrFail($id);
        $user   = $vendor->user;

        // Step 1: Status update
        $user->update(['status' => 'approved']);

        // Step 2: Vendor role assign karo (agar pehle se nahi hai)
        if (!$user->hasRole('vendor')) {
            $user->assignRole('vendor'); // Spatie permission ka method
        }

        return back()->with('success', 'Vendor approved and role assigned successfully.');
    }

    // Reject vendor
    public function reject($id)
    {
        $vendor = VendorProfile::findOrFail($id);
        $vendor->user->update(['status' => 'rejected']);

        return back()->with('success', 'Vendor rejected successfully.');
    }
}
