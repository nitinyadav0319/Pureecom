<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localization extends Model
{
    use HasFactory;
    public static function getDefaultLocationId()
    {
        $location = self::where('is_default', 1)->first();
        return $location ? $location->id : 1;
    }
}
