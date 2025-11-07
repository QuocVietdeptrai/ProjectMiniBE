<?php

use App\Http\Controllers\Api\StudentController;
use Illuminate\Support\Facades\Route;


Route::middleware(['jwt.custom','role:admin,student_manager'])->group(function(){
    Route::get('/students/list', [StudentController::class, 'index']);
    Route::post('/students/create', [StudentController::class, 'store']);
    Route::get('/students/{id}', [StudentController::class, 'show']);
    Route::post('/students/update/{id}', [StudentController::class, 'update']);
    Route::delete('/students/delete/{id}', [StudentController::class, 'destroy']);
});
