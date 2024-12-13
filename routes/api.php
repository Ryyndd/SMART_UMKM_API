<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;


//posts
Route::apiResource('/product', App\Http\Controllers\Api\ProductController::class);


Route::apiResource('/categoryproduct', App\Http\Controllers\Api\ProductCategoryController::class);

Route::apiResource('/user', App\Http\Controllers\Api\UserController::class);

Route::post('/auth/login', [UserController::class,'login']);

Route::get('/user/username/{username}', [UserController::class,'getUserByUsername']);

Route::get('/transaction/user', [TransactionController::class, 'getDataByUser']);

Route::apiResource('/transaction', App\Http\Controllers\Api\TransactionController::class);

