<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transaction_products', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id'); // Tetap menggunakan 'transaction_id' untuk relasi
            $table->string('name');
            $table->decimal('price', 10, 2); // Menggunakan decimal untuk harga
            $table->integer('quantity');
            $table->timestamps();
        
            // Relasi ke transaksi
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_products');
    }
};
