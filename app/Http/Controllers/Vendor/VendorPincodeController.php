<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Pincode;
use App\Models\VendorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorPincodeController extends Controller
{
    /**
     * Show vendor's pincodes page
     */
    public function index()
    {
        $vendor = VendorProfile::where('user_id', Auth::id())->firstOrFail();

        $pincodes = Pincode::orderBy('pincode')->get();
        $assignedPincodes = $vendor->pincodes()->get();

        // 🔹 Unique states aur districts
        $states = Pincode::select('state')->distinct()->orderBy('state')->pluck('state');
        $districts = Pincode::select('district')->distinct()->orderBy('district')->pluck('district');

        return view('vendor.pincodes.index', compact('vendor', 'pincodes', 'assignedPincodes', 'states', 'districts'));
    }


    public function addByRegion(Request $request)
    {
        $vendor = VendorProfile::where('user_id', Auth::id())->firstOrFail();

        $query = Pincode::query();

        if ($request->state) {
            $query->where('state', $request->state);
        }
        if ($request->district) {
            $query->where('district', $request->district);
        }

        $pincodes = $query->pluck('id');

        foreach ($pincodes as $pid) {
            if (!$vendor->pincodes()->where('pincode_id', $pid)->exists()) {
                $vendor->pincodes()->attach($pid);
            }
        }

        return back()->with('success', 'All pincodes from selected region added successfully!');
    }



    public function getDistricts(Request $request)
    {
        $state = $request->state;

        $districts = DB::table('pin_codes')
            ->where('state', $state)
            ->distinct()
            ->pluck('district');

        return response()->json($districts);
    }

    public function getPincodesByDistrict(Request $request)
    {
        $district = $request->district;

        $pincodes = DB::table('pin_codes')
            ->where('district', $district)
            ->select('id', 'pincode')
            ->get();

        return response()->json($pincodes);
    }

    public function addMultiple(Request $request)
    {
        $vendor = VendorProfile::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'pincode_ids' => 'required|array',
        ]);

        foreach ($request->pincode_ids as $pid) {
            if (!$vendor->pincodes()->where('pincode_id', $pid)->exists()) {
                $vendor->pincodes()->attach($pid);
            }
        }

        return back()->with('success', 'Selected pincodes added successfully!');
    }
    public function getDistrictsByState(Request $request)
    {
        $state = $request->state;
        if (!$state)
            return response()->json([]);

        $districts = Pincode::where('state', $state)
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        return response()->json($districts);
    }


    /**
     * Add new pincode to vendor service area
     */
    public function store(Request $request)
    {
        $request->validate([
            'pincode_id' => 'required|exists:pin_codes,id',
        ]);

        $vendor = VendorProfile::where('user_id', Auth::id())->firstOrFail();

        if (!$vendor->pincodes()->where('pincode_id', $request->pincode_id)->exists()) {
            $vendor->pincodes()->attach($request->pincode_id);
        }

        return back()->with('success', 'Pincode added to your service area.');
    }

    /**
     * Remove pincode from vendor service area
     */
    public function destroy($id)
    {
        $vendor = VendorProfile::where('user_id', Auth::id())->firstOrFail();
        $vendor->pincodes()->detach($id);

        return back()->with('success', 'Pincode removed from your service area.');
    }
}
