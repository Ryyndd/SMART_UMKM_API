<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//posts
Route::apiResource('/product', App\Http\Controllers\Api\ProductController::class);

// use App\Http\Controllers\Api\ProductCategoryController;
// use App\Http\Controllers\Api\ProductController;

// Route::prefix('category')->group(function () {
//     // Routes untuk ProductCategory
//     Route::get('/category/product', [ProductCategoryController::class, 'index']);
//     Route::post('/category/product', [ProductCategoryController::class, 'store']);
//     Route::get('/category/product/{category}', [ProductCategoryController::class, 'show']);
//     Route::put('/product/{category}', [ProductCategoryController::class, 'update']);
//     Route::delete('/product/{category}', [ProductCategoryController::class, 'destroy']);
// });


Route::apiResource('/categoryproduct', App\Http\Controllers\Api\ProductCategoryController::class);

Route::post('/user/post', [UserController::class,'store']);

Route::get('/user', [UserController::class,'allUser']);

Route::post('/login', [UserController::class,'index']);

