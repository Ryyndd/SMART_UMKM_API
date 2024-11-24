<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_name',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_product', 'name');
    }
}