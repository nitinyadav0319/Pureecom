<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MegaMenuColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'variation_id',
        'order',
        'is_active'
    ];

    // 🔹 Relation: Categories (many-to-many)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'mega_menu_column_category', 'mega_menu_column_id', 'category_id');
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }
}