<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'username' => 'admin',  // Change this to the desired username
            'password' => bcrypt('password123'), // Hash the password for security
            'api_token' => Str::random(100), // Generate a random API token
            'role' => 'admin', // Change this to the desired role
        ]);
    }
}
