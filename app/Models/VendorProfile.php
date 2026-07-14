<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;  // User model ko import karna mat bhoolna

class VendorProfile extends Model
{
    use HasFactory;


    protected $table = 'vendor_profiles';
    protected $fillable = [
        'business_name',
        'business_type',
        'business_reg_no',
        'establishment_date',
        'business_address',
        'city',
        'state',
        'zip',
        'contact_person',
        'designation',
        'alt_phone',
        'bank_name',
        'branch_name',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'product_categories',
        'avg_order_value',
        'expected_listing_count',
        'business_model',
        'product_certification',
        'pan_number',
        'gst_number',
        'iec_code',
        'kyc_docs',
        'has_own_logistics',
        'preferred_shipping',
        'warehouse_address',
        'agreed_terms'
    ];

    // VendorProfile belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pincodes()
    {
        return $this->belongsToMany(
            Pincode::class,
            'vendor_pincodes',
            'vendor_id',
            'pincode_id'
        )->withTimestamps();
    }
}
