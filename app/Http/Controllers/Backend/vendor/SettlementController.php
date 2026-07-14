<?php

namespace App\Http\Controllers\Backend\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
   public function index()
{
    $vendorId = optional(Auth::user()->vendorProfile)->id;

    if (!$vendorId) {
        abort(403, "Vendor profile missing or user not logged in");
    }

    $orders = OrderItem::where('vendor_id', $vendorId)
        ->with('productVariation.product')
        ->get();

    $totalAdminCommission = $orders->sum('admin_commission');
    $totalVendorEarning   = $orders->sum('vendor_earning');

    return view('backend.vendors.settlement.index', compact(
        'orders',
        'totalAdminCommission',
        'totalVendorEarning'
    ));
}

}
