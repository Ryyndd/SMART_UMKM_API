<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{

    // Menentukan apakah kolom timestamps digunakan
    public $timestamps = true;

    // Menentukan kolom primary key
    protected $primaryKey = 'id_product';

    // Kolom yang dapat diisi
    protected $fillable = [
        'product_image',
        'product_title',
        'description_product',
        'product_price',
        'category_product',
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
