<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//posts
Route::apiResource('/product', App\Http\Controllers\Api\ProductController::class);


Route::apiResource('/categoryproduct', App\Http\Controllers\Api\ProductCategoryController::class);

Route::apiResource('/user', App\Http\Controllers\Api\UserController::class);

Route::post('/auth/login', [UserController::class,'login']);

Route::apiResource('/transaction', App\Http\Controllers\Api\TransactionController::class);

