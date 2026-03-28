<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;

Route::prefix('v1')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctors/{id}', [DoctorController::class, 'show']);
    Route::get('/specializations', [DoctorController::class, 'specializations']);
});
