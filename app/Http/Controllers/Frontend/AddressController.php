<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    # store new address
    public function store(Request $request)
    {
        $userId = auth()->user()->id;

        $address = new UserAddress;
        $address->user_id = $userId;

        // ✅ New fields (string-based)
        $address->pincode = $request->pincode;
        $address->country_name = $request->country_name ?? 'India';
        $address->state_name = $request->state_name;
        $address->district_name = $request->district_name;
        $address->city_name = $request->city_name;
        // ✅ ADD THESE 3 NEW LINES
        $address->village = $request->village;
        $address->house_no = $request->house_no;
        $address->landmark = $request->landmark;
        $address->taluka_name = $request->taluka_name;
        $address->address = $request->address;

        // ✅ Default address logic
        if ($request->is_default == 1) {
            $prevDefault = UserAddress::where('user_id', $userId)
                ->where('is_default', 1)
                ->first();
            if ($prevDefault) {
                $prevDefault->is_default = 0;
                $prevDefault->save();
            }
        }

        $address->is_default = $request->is_default ?? 0;
        $address->save();

        flash(localize('Address has been added successfully'))->success();
        return back();
    }


    # edit address (for modal or form pre-fill)
# edit address (AJAX load for modal)
    public function edit($id)
    {
        $userId = auth()->user()->id;
        $address = UserAddress::where('user_id', $userId)->where('id', $id)->first();

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        // If you want to show dynamic dropdowns (like countries, states, cities)
        $countries = \DB::table('countries')->get();
        $states = \DB::table('states')->get();
        $cities = \DB::table('cities')->get();

        // Return blade partial for modal content
        return view('frontend.default.inc.addressEditForm', compact('address', 'countries', 'states', 'cities'));
    }

    # update address
    public function update(Request $request)
    {
        $userId = auth()->user()->id;
        $address = UserAddress::where('user_id', $userId)
            ->where('id', $request->id)
            ->first();

        // ✅ Update with new string fields
        $address->pincode = $request->pincode;
        $address->country_name = $request->country_name ?? 'India';
        $address->state_name = $request->state_name;
        $address->district_name = $request->district_name;
        $address->city_name = $request->city_name;
        $address->village = $request->village;
        $address->house_no = $request->house_no;
        $address->landmark = $request->landmark;
        $address->taluka_name = $request->taluka_name;
        $address->address = $request->address;

        if ($request->is_default == 1) {
            $prevDefault = UserAddress::where('user_id', $userId)
                ->where('is_default', 1)
                ->first();
            if ($prevDefault) {
                $prevDefault->is_default = 0;
                $prevDefault->save();
            }
        }

        $address->is_default = $request->is_default ?? 0;
        $address->save();

        flash(localize('Address has been updated successfully'))->success();
        return back();
    }

    # delete address
    public function delete($id)
    {
        $user = auth()->user();
        UserAddress::where('user_id', $user->id)
            ->where('id', $id)
            ->delete();

        flash(localize('Address has been deleted successfully'))->success();
        return back();
    }

    # ✅ API to fetch location by pincode (external API instead of DB)
    public function getLocationByPincode(Request $request)
    {
        $pincode = $request->pincode;
        $url = "https://api.postalpincode.in/pincode/" . $pincode;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (!empty($data) && $data[0]['Status'] == "Success") {
            $po = $data[0]['PostOffice'][0];
            return response()->json([
                'state_name' => $po['State'],
                'district_name' => $po['District'],
                'city_name' => $po['Block'] ?? '',
                'message' => 'found'
            ]);
        } else {
            return response()->json(['message' => 'not_found'], 404);
        }
    }
}
