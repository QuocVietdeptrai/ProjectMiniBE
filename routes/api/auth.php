<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/forgotpassword', [AuthController::class, 'forgotpassword']);
Route::post('/otp_password', [AuthController::class, 'otp_password']);
Route::post('/reset_password', [AuthController::class, 'reset_password']);
Route::get('/check-auth', [AuthController::class, 'checkAuth']);

Route::middleware('jwt.custom')->group(function(){
    //tài khoản
    Route::post('/logout', [AuthController::class,'logout']);
    Route::post('/refresh', [AuthController::class,'refresh']);
    Route::get('/me', [AuthController::class,'me']);
    Route::post('/update_profile', [AuthController::class,'update_profile']);
    Route::post('/update_password', [AuthController::class,'update_password']);
    
});
