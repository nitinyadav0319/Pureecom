<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'order_id',
        'product_variation_id',
        'qty',
        'location_id',
        'unit_price',
        'total_tax',
        'total_price',
        'reward_points',
        'is_refunded',
        'admin_commission', // naya column
        'vendor_earning',   // naya column
    ];

    public function product_variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }

    // OrderItem.php
public function product()
{
    return $this->hasOneThrough(
        Product::class,          // Final model
        ProductVariation::class, // Intermediate model
        'id',                    // ProductVariation.pk
        'id',                    // Product.pk
        'product_variation_id',  // OrderItem.product_variation_id
        'product_id'             // ProductVariation.product_id
    );
}
public function productVariation()
{
    return $this->belongsTo(\App\Models\ProductVariation::class, 'product_variation_id');
}


    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function refundRequest()
    {
        return $this->hasOne(Refund::class);
    }

    /**
     * Admin commission calculate karo
     */
    public function adminCommission()
    {
        $category = $this->product->category ?? null;
        $commissionRate = $category->commission_percentage ?? 0;
        return ($this->total_price * $commissionRate) / 100;
    }

    /**
     * Vendor earning calculate karo
     */
    public function vendorEarning()
    {
        return $this->total_price - $this->adminCommission();
    }
}
// protected $fillable = [
    //     'vendor_id',
    //     'order_id',
    //     'product_variation_id',
    //     'qty',
    //     'location_id',
    //     'unit_price',
    //     'total_tax',
    //     'total_price',
    //     'reward_points',
    //     'is_refunded',
    // ];