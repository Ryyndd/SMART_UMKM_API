<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Menambahkan produk dalam transaksi pertama
        TransactionProduct::create([
            'transaction_product_id' => 'TX12345',
            'name' => 'Smartphone',
            'price' => '3000000',
            'quantity' => 1,
        ]);

        TransactionProduct::create([
            'transaction_product_id' => 'TX12345',
            'name' => 'T-Shirt',
            'price' => '150000',
            'quantity' => 2,
        ]);

        // Menambahkan produk dalam transaksi kedua
        TransactionProduct::create([
            'transaction_product_id' => 'TX12346',
            'name' => 'Sofa',
            'price' => '1200000',
            'quantity' => 1,
        ]);
    }
}
