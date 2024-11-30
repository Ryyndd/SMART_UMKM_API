<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{

    public $timestamps = true;

    // protected $primaryKey = 'id';

    // Kolom yang dapat diisi
    protected $fillable = [
        'image',
        'name',
        'description',
        'price',
        'category',
    ];

    // Mengubah format kolom product_image untuk menambahkan URL
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/product/' . $image), // Menambahkan URL ke image
        );
    }

    // Relasi dengan kategori produk (ProductCategory)
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_product', 'name');
    }
}
