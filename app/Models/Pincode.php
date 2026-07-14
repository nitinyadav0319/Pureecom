<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    use HasFactory;
    protected $table = 'pin_codes'; // 👈 ye line add karni hai

    protected $fillable = [
        'pincode',
        'district',
        'state',
        'is_active'
    ];
    public function vendors()
    {
        return $this->belongsToMany(
            VendorProfile::class,
            'vendor_pincodes',
            'pincode_id',
            'vendor_id'
        )->withTimestamps();
    }
}
