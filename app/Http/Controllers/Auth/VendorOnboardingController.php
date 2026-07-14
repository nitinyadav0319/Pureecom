<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorProfile;
use App\Models\Category;

class VendorOnboardingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // vendor login required
    }

    // Step 1: Business Information
    public function step1()
    {
        $vendor = Auth::user()->vendorProfile;
        return view('auth.vendor.onboarding.step1', compact('vendor'));
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'business_reg_no' => 'required|string|max:255',
            'establishment_date' => 'required|date',
            'business_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
        ]);

        $vendor = Auth::user()->vendorProfile ?? new VendorProfile();
        $vendor->user_id = Auth::id();
        $vendor->fill($request->all());
        $vendor->save();

        return redirect()->route('vendor.onboarding.step2');

    }

    // Step 2: Contact Information
public function step2()
{
    $vendor = Auth::user()->vendorProfile ?? new VendorProfile();
    return view('auth.vendor.onboarding.step2', compact('vendor'));
}

public function storeStep2(Request $request)
{
    $request->validate([
        'contact_person' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'alt_phone' => 'nullable|string|max:20',
    ]);

    $vendor = Auth::user()->vendorProfile ?? new VendorProfile();
    $vendor->user_id = Auth::id();
    $vendor->fill($request->all());
    $vendor->save();

    return redirect()->route('vendor.onboarding.step3');
}

// Step 3: Bank Details
public function step3()
{
    $vendor = Auth::user()->vendorProfile ?? new VendorProfile();
    return view('auth.vendor.onboarding.step3', compact('vendor'));
}

public function storeStep3(Request $request)
{
    $request->validate([
        'bank_name' => 'required|string',
        'branch_name' => 'required|string',
        'account_holder_name' => 'required|string',
        'account_number' => 'required|string',
        'ifsc_code' => 'required|string',
    ]);

    $vendor = Auth::user()->vendorProfile ?? new VendorProfile();
    $vendor->user_id = Auth::id();
    $vendor->fill($request->all());
    $vendor->save();

    return redirect()->route('vendor.onboarding.step4');
}


    // Step 4: Product Information
   

public function step4()
{
    $vendor = Auth::user()->vendorProfile;
    $categories = Category::all(); // Ye tumhare B2C categories ko fetch karega
    return view('auth.vendor.onboarding.step4', compact('vendor', 'categories'));
}

public function storeStep4(Request $request)
{
    $request->validate([
        'product_categories' => 'required|array', // multiple select ke liye array
        'avg_order_value' => 'required|string',
        'expected_listing_count' => 'required|integer',
        'business_model' => 'required|in:manufacturer,reseller',
        'product_certification' => 'nullable|string',
    ]);

    $vendor = Auth::user()->vendorProfile;

    // Multiple categories ko comma-separated string me save karenge
    $vendor->product_categories = implode(',', $request->product_categories);
    $vendor->avg_order_value = $request->avg_order_value;
    $vendor->expected_listing_count = $request->expected_listing_count;
    $vendor->business_model = $request->business_model;
    $vendor->product_certification = $request->product_certification;

    $vendor->save();

    return redirect()->route('vendor.onboarding.step5');
}

    // Step 5: Tax & Compliance
    public function step5()
    {
        $vendor = Auth::user()->vendorProfile;
        return view('auth.vendor.onboarding.step5', compact('vendor'));
    }

    public function storeStep5(Request $request)
     {
        $request->validate([
            'pan_number' => 'required|string',
            'gst_number' => 'nullable|string',
            'iec_code' => 'nullable|string',
            'kyc_docs.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $vendor = Auth::user()->vendorProfile ?? new VendorProfile();
        $vendor->user_id = Auth::id();
        $vendor->fill($request->only(['pan_number','gst_number','iec_code']));

        // Handle multiple KYC uploads
        if($request->hasFile('kyc_docs')){
            $files = $request->file('kyc_docs');
            $filenames = [];

            foreach($files as $file){
                $filename = time().'_'.$file->getClientOriginalName();
                $file->storeAs('public/kyc_docs', $filename);
                $filenames[] = $filename;
            }

            $vendor->kyc_docs = implode(',', $filenames);
        }

        $vendor->save();

        return redirect()->route('vendor.onboarding.step6');
    }
    // Step 6: Logistics & Fulfillment
    public function step6()
    {
        $vendor = Auth::user()->vendorProfile;
        return view('auth.vendor.onboarding.step6', compact('vendor'));
    }

    public function storeStep6(Request $request)
    {
        $request->validate([
            'has_own_logistics' => 'required|boolean',
            'preferred_shipping' => 'nullable|string',
            'warehouse_address' => 'nullable|string',
        ]);

        $vendor = Auth::user()->vendorProfile ?? new VendorProfile();
    $vendor->user_id = Auth::id();
    $vendor->fill($request->all());
    $vendor->save();

        return redirect()->route('vendor.onboarding.step7');
    }

    // Step 7: Terms & Agreement
    public function step7()
    {
        $vendor = Auth::user()->vendorProfile;
        return view('auth.vendor.onboarding.step7', compact('vendor'));
    }

    public function storeStep7(Request $request)
{
    $request->validate([
        'agreed_terms' => 'required|accepted',
    ]);

    $vendor = Auth::user()->vendorProfile;
    $vendor->agreed_terms = true;
    $vendor->step_completed = 7;  // Mark onboarding complete
    $vendor->save();

    $user = Auth::user();
    $user->status = 'pending';  // Match middleware condition
    $user->save();

    return redirect()->route('vendor.pending')
                     ->with('status', 'Wait for your approval');
}
}