<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\DoctorMobileController;
use App\Http\Controllers\Api\HospitalController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PregnancyTrackingController;
use App\Http\Controllers\Api\PeriodTrackingController;
use App\Http\Middleware\ApiTokenMiddleware;

Route::prefix('v1')->group(function () {

    // App version check
    Route::get('/app-version', [UserController::class, 'appVersion']);

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
        Route::post('/fcm-token',           [UserController::class, 'updateFcmToken']);
        Route::get('/my-bookings',          [UserController::class, 'myBookings']);
        Route::post('/reschedule-booking',   [UserController::class, 'rescheduleBooking']);
        Route::post('/cancel-booking',        [UserController::class, 'cancelBooking']);
        Route::post('/book-appointment',     [DoctorController::class, 'bookAppointment']);
        Route::get('/period-tracking',        [PeriodTrackingController::class, 'get']);
        Route::post('/period-tracking',       [PeriodTrackingController::class, 'save']);
        Route::get('/pregnancy-tracking',     [PregnancyTrackingController::class, 'get']);
        Route::post('/pregnancy-tracking',    [PregnancyTrackingController::class, 'save']);
    });

    // ── Doctor Mobile App Routes ──────────────────────────────────────────────
    // Public
    Route::post('/doctor/login', [DoctorMobileController::class, 'login']);

    // Protected (token required)
    Route::middleware(ApiTokenMiddleware::class)->prefix('doctor')->group(function () {
        Route::post('/logout',                    [DoctorMobileController::class, 'logout']);
        Route::get('/dashboard',                  [DoctorMobileController::class, 'dashboard']);
        Route::get('/test-notification',          [DoctorMobileController::class, 'testNotification']);

        // Profile
        Route::get('/profile',                    [DoctorMobileController::class, 'profile']);
        Route::post('/profile/update',            [DoctorMobileController::class, 'updateProfile']);

        // Appointments
        Route::get('/appointments',               [DoctorMobileController::class, 'appointments']);
        Route::post('/appointments/add',          [DoctorMobileController::class, 'addAppointment']);
        Route::post('/appointments/cancel/{id}',  [DoctorMobileController::class, 'cancelAppointment']);
        Route::get('/invoice-masters',            [DoctorMobileController::class, 'invoiceMasters']);
        Route::post('/invoice-masters/save',      [DoctorMobileController::class, 'saveInvoiceMaster']);
        Route::delete('/invoice-masters/{id}',    [DoctorMobileController::class, 'deleteInvoiceMaster']);
        Route::post('/booked-slots',              [DoctorMobileController::class, 'bookedSlots']);
        Route::post('/fcm-token',                 [DoctorMobileController::class, 'saveFcmToken']);

        // Staff
        Route::get('/staff',                      [DoctorMobileController::class, 'staff']);
        Route::post('/staff/save',                [DoctorMobileController::class, 'saveStaff']);
        Route::delete('/staff/{id}',              [DoctorMobileController::class, 'deleteStaff']);

        // Staff Attendance
        Route::get('/staff/attendance',           [DoctorMobileController::class, 'staffAttendance']);
        Route::post('/staff/attendance/save',     [DoctorMobileController::class, 'saveAttendance']);
        Route::get('/staff/attendance/report',    [DoctorMobileController::class, 'attendanceReport']);
    });

});
