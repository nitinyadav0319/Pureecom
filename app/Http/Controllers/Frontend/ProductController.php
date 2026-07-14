<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductVariationInfoResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\ProductVariation;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Models\Variation;
use App\Models\VariationValue;
class ProductController extends Controller
{
    # product listing
    public function index(Request $request)
    {
        $searchKey = null;
        $per_page = 9;
        $sort_by = $request->sort_by ? $request->sort_by : "new";
        $maxRange = Product::max('max_price');
        $min_value = 0;
        $max_value = formatPrice($maxRange, false, false, false, false);

        $products = Product::isPublished();
        $ageVariation = Variation::where('name', 'Age')->first();
        $ageValues = collect();

        if ($ageVariation) {
            $ageValues = VariationValue::where('variation_id', $ageVariation->id)->pluck('name');
        }
        $sizeVariation = Variation::where('name', 'Size')->first();
        $sizeValues = collect();
        if ($sizeVariation) {
            $sizeValues = VariationValue::where('variation_id', $sizeVariation->id)->pluck('name');
        }

        # conditional - search by
        if ($request->search != null) {
            $products = $products->where('name', 'like', '%' . $request->search . '%');
            $searchKey = $request->search;
        }

        # pagination
        if ($request->per_page != null) {
            $per_page = $request->per_page;
        }

        # sort by
        if ($sort_by == 'new') {
            $products = $products->latest();
        } else {
            $products = $products->orderBy('total_sale_count', 'DESC');
        }

        # by price
        if ($request->min_price != null) {
            $min_value = $request->min_price;
        }
        if ($request->max_price != null) {
            $max_value = $request->max_price;
        }

        if ($request->min_price || $request->max_price) {
            $products = $products->where('min_price', '>=', priceToUsd($min_value))->where('min_price', '<=', priceToUsd($max_value));
        }
        // Filter by Age
        if ($request->age && $request->age != null) {
            $ages = is_array($request->age) ? $request->age : [$request->age];

            $products = $products->whereHas('variations', function ($q) use ($ages) {
                $q->where(function ($query) use ($ages) {
                    foreach ($ages as $age) {
                        $query->orWhere('code', 'LIKE', '%' . $age . '%');
                    }
                });
            });
        }



        // ✅ Filter by Size
        if ($request->size && $request->size != null) {
            $sizes = is_array($request->size) ? $request->size : [$request->size];

            $products = $products->whereHas('variations', function ($q) use ($sizes) {
                foreach ($sizes as $size) {
                    $q->where('code', 'LIKE', '%' . $size . '%');
                }
            });
        }






        // Build Your Box Category Check
        $category = null;

        if ($request->category_id) {

            $category = \App\Models\Category::find($request->category_id);

            if ($category && $category->is_box_category) {

                $productIds = ProductCategory::where('category_id', $category->id)
                    ->pluck('product_id');

                $products = Product::isPublished()
                    ->whereIn('id', $productIds)
                    ->get();

                return getView('pages.build-box.index', [
                    'category' => $category,
                    'products' => $products
                ]);
            }
        }

        # by category
        if ($request->category_id && $request->category_id != null) {
            $product_category_product_ids = ProductCategory::where('category_id', $request->category_id)->pluck('product_id');
            $products = $products->whereIn('id', $product_category_product_ids);
        }

        # by tag
        if ($request->tag_id && $request->tag_id != null) {
            $product_tag_product_ids = ProductTag::where('tag_id', $request->tag_id)->pluck('product_id');
            $products = $products->whereIn('id', $product_tag_product_ids);
        }
        # conditional

        $products = $products->paginate(paginationNumber($per_page));

        $tags = Tag::all();
        return getView('pages.products.index', [
            'products' => $products,
            'searchKey' => $searchKey,
            'per_page' => $per_page,
            'sort_by' => $sort_by,
            'max_range' => formatPrice($maxRange, false, false, false, false),
            'min_value' => $min_value,
            'max_value' => $max_value,
            'tags' => $tags,
            'ageValues' => $ageValues,
            'sizeValues' => $sizeValues,
        ]);
    }

    # product show
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (auth()->check() && auth()->user()->user_type == "admin") {
            // do nothing
        } else {
            if ($product->is_published == 0) {
                flash(localize('This product is not available'))->info();
                return redirect()->route('home');
            }
        }

        $productCategories = $product->categories()->pluck('category_id');
        $productIdsWithTheseCategories = ProductCategory::whereIn('category_id', $productCategories)->where('product_id', '!=', $product->id)->pluck('product_id');

        $relatedProducts = Product::whereIn('id', $productIdsWithTheseCategories)->get();

        $product_page_widgets = [];
        if (getSetting('product_page_widgets') != null) {
            $product_page_widgets = json_decode(getSetting('product_page_widgets'));
        }

        return getView('pages.products.show', ['product' => $product, 'relatedProducts' => $relatedProducts, 'product_page_widgets' => $product_page_widgets]);
    }

    # product info
    public function showInfo(Request $request)
    {
        $product = Product::find($request->id);
        return getView('pages.partials.products.product-view-box', ['product' => $product]);
    }

    # product variation info
    public function getVariationInfo(Request $request)
    {
        $variationKey = "";
        foreach ($request->variation_id as $variationId) {
            $fieldName = 'variation_value_for_variation_' . $variationId;
            $variationKey .= $variationId . ':' . $request[$fieldName] . '/';
        }
        $productVariation = ProductVariation::where('variation_key', $variationKey)->where('product_id', $request->product_id)->first();

        return new ProductVariationInfoResource($productVariation);
    }


    public function checkDelivery(Request $request)
    {
        $pincode = $request->pincode;

        // ✅ Vendor profile nikaalo
        $product = Product::find($request->product_id);

        if (!$product) {
            session()->forget('user_pincode');
            return back()->with('delivery_message', 'Invalid product!');
        }

        // ✅ Check karo ki vendor ke mapped pincodes me ye pincode hai ya nahi
        $isAvailable = $product->isDeliverableToPincode($pincode);

        if ($isAvailable) {
            // ✅ Save pincode to session
            session(['user_pincode' => $pincode]);

            return back()->with('delivery_message', '✅ Delivery available to your area!');
        } else {
            // ❌ Clear old session if not deliverable
            session()->forget('user_pincode');

            return back()->with('delivery_message', '❌ Sorry, delivery not available in this pincode.');
        }
    }


    public function buildBoxAddToCart(Request $request)
{
    $variationIds = $request->variation_ids;

    if (!$variationIds || count($variationIds) == 0) {
        return response()->json([
            'success' => false,
            'message' => 'No products selected'
        ]);
    }

    foreach ($variationIds as $variationId) {

        $productVariation = \App\Models\ProductVariation::find($variationId);

        if (!$productVariation) {
            continue;
        }

        $cart = new \App\Models\Cart();

        $cart->product_variation_id = $productVariation->id;
        $cart->qty = 1;
        $cart->location_id = session('stock_location_id');

        if (auth()->check()) {
            $cart->user_id = auth()->id();
        } else {
            $cart->guest_user_id = (int) $_COOKIE['guest_user_id'];
        }

        $cart->save();
    }

    return response()->json([
        'success' => true
    ]);
}



}
