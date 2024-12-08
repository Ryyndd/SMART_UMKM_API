<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Kolom ID sebagai primary key default
            $table->string('image')->default('');
            $table->string('name'); 
            $table->string('email');
            $table->string('phone');
            $table->string('username')->unique(); // Username unik
            $table->string('password'); // Hash password
            $table->string('role'); // Role (admin atau user)
            $table->timestamps(); // Created_at dan updated_at
        });
        

        // Create 'password_reset_tokens' table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('username')->primary(); // Username as primary key
            $table->string('token'); // Reset token
            $table->timestamp('created_at')->nullable(); // Token creation time
        });

        // Create 'sessions' table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Session ID as primary key
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->index(); // Foreign key to users
            $table->string('ip_address', 45)->nullable(); // IPv4/IPv6 address
            $table->text('user_agent')->nullable(); // Browser user agent
            $table->longText('payload'); // Session payload
            $table->integer('last_activity')->index(); // Last activity timestamp
        });
    }

    public function down(): void
    {
        // Drop tables in reverse order to avoid foreign key issues
        // Schema::dropIfExists('sessions');
        // Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
