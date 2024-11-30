<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Menambahkan beberapa kategori produk contoh
        ProductCategory::create(['name' => 'Elektronik']);
        ProductCategory::create(['name' => 'Pakaian']);
        ProductCategory::create(['name' => 'Makanan']);
        ProductCategory::create(['name' => 'Peralatan Rumah Tangga']);
    }
}
