<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{

    use HasFactory, Notifiable;

    protected $table = 'users';
    // protected $primaryKey = 'user_id';
    

    protected $fillable = [
        'image',
        'name',
        'email',
        'phone',
        'username',
        'password',
        'role',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/user/' . $image), // Menambahkan URL ke image
        );
    }

    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    //     // 'password' => 'hashed', // Uncomment if you're using password hashing in Laravel 8+
    // ];

}
