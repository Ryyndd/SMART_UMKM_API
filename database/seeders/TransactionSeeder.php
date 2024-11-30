<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Menambahkan beberapa transaksi contoh
        Transaction::create([
            'transaction_id' => 'TX12345',
            'transaction_time' => now(),
            'transaction_user' => 'User1',
            'transaction_total' => 5000000,
            'transaction_cashback' => 50000,
        ]);

        Transaction::create([
            'transaction_id' => 'TX12346',
            'transaction_time' => now(),
            'transaction_user' => 'User2',
            'transaction_total' => 700000,
            'transaction_cashback' => 7000,
        ]);
    }
}
