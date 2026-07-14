<?php

namespace App\Http\Controllers\Backend\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Session;


class VendorController extends Controller
{
    // Vendor List page
    public function list()
    {
        $vendors = User::where('user_type', 'vendor')
            ->with('vendorProfile')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('backend.vendors.list', compact('vendors'));
    }

    // Vendor Details page (with products & orders)
    public function show($id)
    {
        // Vendor details + profile
        $vendor = User::with('vendorProfile')
            ->where('id', $id)
            ->where('user_type', 'vendor')
            ->firstOrFail();

        // Vendor profile ID
        $vendorProfileId = optional($vendor->vendorProfile)->id;

        // Agar profile nahi hai to empty collection
        if (!$vendorProfileId) {
            $products = collect();
            $orders = collect();
        } else {
            // Products: vendor_id = vendorProfile.id
            $products = Product::where('vendor_id', $vendorProfileId)
                ->latest()
                ->get();

            // Orders: orderItems.vendor_id = vendorProfile.id
            $orders = Order::whereHas('orderItems', function ($q) use ($vendorProfileId) {
                $q->where('vendor_id', $vendorProfileId);
            })
                ->with([
                    'orderItems' => function ($q) use ($vendorProfileId) {
                        $q->where('vendor_id', $vendorProfileId)->with('product');
                    }
                ])
                ->latest()
                ->get();
        }

        return view('backend.vendors.show', compact('vendor', 'products', 'orders'));
    }
    public function approveProduct($id)
    {
        $product = Product::findOrFail($id);

        // Status update
        $product->status = 'approved';
        $product->is_published = 1; // frontend pe show kare
        $product->save();

        // Flash message
        Session::flash('success', 'Product approved successfully.');

        // Redirect back to vendor details page
        return redirect()->back();
    }

    /**
     * Reject vendor product
     */
    public function rejectProduct($id)
    {
        $product = Product::findOrFail($id);

        // Status update
        $product->status = 'rejected';
        $product->is_published = 0; // frontend se hide
        $product->save();

        // Flash message
        Session::flash('success', 'Product rejected successfully.');

        // Redirect back to vendor details page
        return redirect()->back();
    }
}
