<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_products', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_product_id');
            $table->string('name');
            $table->string('price'); // Harga item
            $table->integer('quantity');
            $table->timestamps();
        
            // Relasi ke transaksi
            $table->foreign('transaction_product_id')->references('transaction_id')->on('transactions')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_products');
    }
};
