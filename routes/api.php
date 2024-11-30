<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//posts
Route::apiResource('/product', App\Http\Controllers\Api\ProductController::class);


Route::apiResource('/categoryproduct', App\Http\Controllers\Api\ProductCategoryController::class);

Route::post('/user/post', [UserController::class,'store']);

Route::get('/user', [UserController::class,'allUser']);

Route::post('/auth/login', [UserController::class,'index']);

Route::apiResource('/transaction', App\Http\Controllers\Api\TransactionController::class);

