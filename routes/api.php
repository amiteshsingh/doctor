<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\DoctorMobileController;
use App\Http\Controllers\Api\HospitalController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PregnancyTrackingController;
use App\Http\Controllers\Api\PeriodTrackingController;
use App\Http\Controllers\Api\ChildVaccineController;
use App\Http\Controllers\Api\PaymentController;
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
    Route::get('/cities',            [DoctorMobileController::class, 'cities']);
    Route::match(['get','post'], '/booked-slots', [DoctorController::class, 'bookedSlots']);

    // Hospitals (public)
    Route::get('/hospitals',         [HospitalController::class, 'index']);
    Route::get('/hospitals/{id}',    [HospitalController::class, 'show']);

    // Payment (public — settings fetch)
    Route::get('/payment-settings',  [PaymentController::class, 'settings']);

    // Protected routes (token required)
    Route::middleware(ApiTokenMiddleware::class)->group(function () {
        Route::get('/profile',              [UserController::class, 'profile']);
        Route::post('/profile/update',      [UserController::class, 'updateProfile']);
        Route::post('/fcm-token',           [UserController::class, 'updateFcmToken']);
        Route::get('/my-bookings',          [UserController::class, 'myBookings']);
        Route::post('/reschedule-booking',  [UserController::class, 'rescheduleBooking']);
        Route::post('/cancel-booking',      [UserController::class, 'cancelBooking']);
        Route::post('/book-appointment',    [DoctorController::class, 'bookAppointment']);
        Route::get('/period-tracking',      [PeriodTrackingController::class, 'get']);
        Route::post('/period-tracking',     [PeriodTrackingController::class, 'save']);
        Route::get('/pregnancy-tracking',   [PregnancyTrackingController::class, 'get']);
        Route::post('/pregnancy-tracking',  [PregnancyTrackingController::class, 'save']);
        Route::get('/children',             [ChildVaccineController::class, 'getChildren']);
        Route::post('/children/add',        [ChildVaccineController::class, 'addChild']);
        Route::post('/children/vaccine',    [ChildVaccineController::class, 'markVaccine']);
        Route::delete('/children/{id}',     [ChildVaccineController::class, 'deleteChild']);
        Route::post('/payment/order',        [PaymentController::class, 'createOrder']);
        Route::post('/payment/verify',       [PaymentController::class, 'verifyPayment']);
    });

    // One-time: migrate old doctor pics from storage to public/uploads/doctor
    Route::get('/migrate-doctor-pics', function () {
        $src = storage_path('app/public/upload/doctor');
        $dst = public_path('uploads/doctor');
        if (!is_dir($src)) return response()->json(['msg' => 'Source not found', 'src' => $src]);
        $files = scandir($src);
        $moved = [];
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $from = $src . DIRECTORY_SEPARATOR . $f;
            $to   = $dst . DIRECTORY_SEPARATOR . $f;
            if (!file_exists($to)) { copy($from, $to); $moved[] = $f; }
        }
        return response()->json(['msg' => 'Done', 'moved' => $moved]);
    });

    // Debug: check paths and folder permissions on live server
    Route::get('/debug-paths', function () {
        $dst = public_path('uploads/doctor');
        $dst2 = base_path('../uploads/doctor');
        return response()->json([
            'public_path'    => public_path(),
            'base_path'      => base_path(),
            'uploads_doctor' => $dst,
            'dir_exists'     => is_dir($dst),
            'is_writable'    => is_writable($dst),
            'alt_path'       => $dst2,
            'alt_exists'     => is_dir($dst2),
            'alt_writable'   => is_dir($dst2) ? is_writable($dst2) : false,
            'files'          => is_dir($dst) ? array_slice(scandir($dst), 2, 5) : [],
        ]);
    });

    // ── Doctor Mobile App Routes ──────────────────────────────────────────────
    Route::post('/doctor/login',           [DoctorMobileController::class, 'login']);
    Route::post('/doctor/register',         [DoctorMobileController::class, 'register']);
    Route::post('/doctor/forgot-password',  [DoctorMobileController::class, 'forgotPassword']);
    Route::post('/doctor/verify-otp',       [DoctorMobileController::class, 'verifyOtp']);

    Route::middleware(ApiTokenMiddleware::class)->prefix('doctor')->group(function () {
        Route::post('/logout',                   [DoctorMobileController::class, 'logout']);
        Route::get('/dashboard',                 [DoctorMobileController::class, 'dashboard']);
        Route::get('/test-notification',         [DoctorMobileController::class, 'testNotification']);

        // Profile
        Route::get('/profile',                   [DoctorMobileController::class, 'profile']);
        Route::post('/profile/update',           [DoctorMobileController::class, 'updateProfile']);

        // Appointments
        Route::get('/appointments',              [DoctorMobileController::class, 'appointments']);
        Route::post('/appointments/add',         [DoctorMobileController::class, 'addAppointment']);
        Route::post('/appointments/cancel/{id}', [DoctorMobileController::class, 'cancelAppointment']);

        // Invoice Masters
        Route::get('/invoice-masters',           [DoctorMobileController::class, 'invoiceMasters']);
        Route::post('/invoice-masters/save',     [DoctorMobileController::class, 'saveInvoiceMaster']);
        Route::delete('/invoice-masters/{id}',   [DoctorMobileController::class, 'deleteInvoiceMaster']);

        // Booking
        Route::post('/booking-toggle',           [DoctorMobileController::class, 'toggleOnlineBooking']);
        Route::post('/booked-slots',             [DoctorMobileController::class, 'bookedSlots']);
        Route::post('/fcm-token',                [DoctorMobileController::class, 'saveFcmToken']);

        // My Doctors
        Route::get('/my-doctors',                    [DoctorMobileController::class, 'myDoctors']);
        Route::post('/my-doctors/save',              [DoctorMobileController::class, 'saveMyDoctor']);
        Route::post('/my-doctors/specializations',   [DoctorMobileController::class, 'saveDoctorSpecializations']);
        Route::post('/my-doctors/location',          [DoctorMobileController::class, 'saveDoctorLocation']);
        Route::post('/my-doctors/availability',      [DoctorMobileController::class, 'saveDoctorAvailability']);
        Route::post('/my-doctors/gallery',           [DoctorMobileController::class, 'saveDoctorGallery']);
        Route::delete('/my-doctors/gallery/{id}',    [DoctorMobileController::class, 'deleteDoctorGallery']);
        Route::post('/my-doctors/toggle/{id}',       [DoctorMobileController::class, 'toggleDoctorStatus']);
        Route::get('/my-doctors/{id}/detail',        [DoctorMobileController::class, 'myDoctorDetail']);
        Route::delete('/my-doctors/{id}',            [DoctorMobileController::class, 'deleteMyDoctor']);

        // Staff
        Route::get('/staff',                     [DoctorMobileController::class, 'staff']);
        Route::post('/staff/save',               [DoctorMobileController::class, 'saveStaff']);
        Route::delete('/staff/{id}',             [DoctorMobileController::class, 'deleteStaff']);

        // Staff Attendance
        Route::get('/staff/attendance',          [DoctorMobileController::class, 'staffAttendance']);
        Route::post('/staff/attendance/save',    [DoctorMobileController::class, 'saveAttendance']);
        Route::get('/staff/attendance/report',   [DoctorMobileController::class, 'attendanceReport']);
    });

});
