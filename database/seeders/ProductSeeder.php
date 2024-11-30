<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Mendapatkan id kategori yang ada
        $categories = ProductCategory::all();

        // Menambahkan beberapa produk contoh
        Product::create([
            'image' => 'product1.jpg',
            'name' => 'Smartphone',
            'description' => 'Smartphone dengan spesifikasi tinggi.',
            'price' => 3000000,
            'category' => $categories->first()->name, // Menggunakan kategori pertama
        ]);

        Product::create([
            'image' => 'product2.jpg',
            'name' => 'T-Shirt',
            'description' => 'T-Shirt nyaman untuk sehari-hari.',
            'price' => 150000,
            'category' => $categories->get(1)->name, // Menggunakan kategori kedua
        ]);

        Product::create([
            'image' => 'product3.jpg',
            'name' => 'Sofa',
            'description' => 'Sofa empuk untuk ruang tamu.',
            'price' => 1200000,
            'category' => $categories->get(3)->name, // Menggunakan kategori keempat
        ]);
    }
}
