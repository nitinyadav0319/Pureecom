<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\UserAddress;
use App\Models\ProductVariation;

use App\Models\Pincode;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = auth()->user();

        // Step 1: Check selected address
        $address = UserAddress::find($request->shipping_address_id);
        if (!$address) {
            return back()->with('error', 'Please select a valid shipping address.');
        }

        // Step 2: Get address pincode
        $addressPincode = $address->pincode;
        if (!$addressPincode) {
            return back()->with('error', 'Address does not have a valid pincode.');
        }

        // Step 3: Get user cart items
        $carts = Cart::where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Step 4: Loop through cart items
        foreach ($carts as $cart) {
            // get product_variation
            $variation = ProductVariation::find($cart->product_variation_id);
            if (!$variation)
                continue;

            $product = $variation->product;

            // Step 5: Check if product delivers to this pincode
            if (!$product->isDeliverableToPincode($addressPincode)) {
                return back()->with('error', "Product does not deliver to pincode {$addressPincode}.");
            }

            // Step 6: Create order
            Order::create([
                'user_id' => $user->id,
                'vendor_id' => $product->vendor_id,
                'product_variation_id' => $variation->id,
                'qty' => $cart->qty,
                'address_id' => $address->id,
                'status' => 'pending'
            ]);
        }

        // Step 7: Clear cart
        Cart::where('user_id', $user->id)->delete();

        return back()->with('success', 'Order placed successfully!');
    }
}
