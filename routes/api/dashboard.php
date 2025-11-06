<?php

use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;


Route::middleware(['jwt.custom', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard/summary', [DashboardController::class, 'summary']);
});