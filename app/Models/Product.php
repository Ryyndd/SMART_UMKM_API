<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    public $timestamps = true;
    //
    protected $fillable = [
        'product_image',
        'product_title',
        'product_price',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/product/' . $image),
        );
    }
}
