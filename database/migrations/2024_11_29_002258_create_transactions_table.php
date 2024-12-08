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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id')->primary(); // Mengubah nama kolom menjadi 'id'
            $table->string('time'); // Mengubah nama kolom menjadi 'time'
            $table->string('user'); // Mengubah nama kolom menjadi 'user'
            $table->string('total'); // Mengubah nama kolom menjadi 'total'
            $table->string('cashback'); // Mengubah nama kolom menjadi 'cashback'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
