<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

Route::middleware(['jwt.custom','role:admin,order_manager'])->group(function(){
    Route::get('/orders/list', [OrderController::class, 'index']);
    Route::post('/orders/create', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders/update/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/delete/{id}', [OrderController::class, 'destroy']);
});
