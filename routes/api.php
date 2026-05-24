<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\HospitalController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\ApiTokenMiddleware;

Route::prefix('v1')->group(function () {

    // Public routes
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login',    [UserController::class, 'login']);

    // Doctors (public)
    Route::get('/doctors',           [DoctorController::class, 'index']);
    Route::get('/doctors/{id}',      [DoctorController::class, 'show']);
    Route::get('/specializations',   [DoctorController::class, 'specializations']);
    Route::match(['get','post'], '/booked-slots', [DoctorController::class, 'bookedSlots']);

    // Hospitals (public)
    Route::get('/hospitals',         [HospitalController::class, 'index']);
    Route::get('/hospitals/{id}',    [HospitalController::class, 'show']);

    // Protected routes (token required)
    Route::middleware(ApiTokenMiddleware::class)->group(function () {
        Route::get('/profile',              [UserController::class, 'profile']);
        Route::post('/profile/update',      [UserController::class, 'updateProfile']);
        Route::get('/my-bookings',          [UserController::class, 'myBookings']);
        Route::post('/book-appointment',    [DoctorController::class, 'bookAppointment']);
    });

});
