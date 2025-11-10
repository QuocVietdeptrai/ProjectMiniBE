<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::middleware(['jwt.custom','role:admin,product_manager'])->group(function(){
    Route::get('/products/list', [ProductController::class, 'index']);
    Route::get('/products/listOrder', [ProductController::class, 'indexOrder']);
    Route::post('/products/create', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products/update/{id}', [ProductController::class, 'update']);
    Route::delete('/products/delete/{id}', [ProductController::class, 'destroy']);
});
