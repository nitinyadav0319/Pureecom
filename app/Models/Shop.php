<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'slug',
        'is_approved',
        'is_published',
        'current_balance',
        'shop_logo',
    ];

    /**
     * Scope to get only approved shops (exclude admin shop)
     */
    public function scopeIsApproved($query)
    {
        return $query->where('is_approved', 1)
                     ->where('user_id', '!=', 1); // 1 = admin shop
    }

    /**
     * Relation: Shop belongs to a User (Vendor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Static helper to create or get vendor shop automatically
     */
    public static function getOrCreateVendorShop($vendorId, $businessName)
    {
        return self::firstOrCreate(
            ['user_id' => $vendorId], // unique per vendor
            [
                'shop_name'     => $businessName,
                'slug'          => Str::slug($businessName),
                'is_approved'   => 1,
                'is_published'  => 1,
                'current_balance'=> 0,
            ]
        );
    }
}
