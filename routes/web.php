<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HospitalController;

use App\Http\Controllers\Doctor\DoctorController;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/', [PageController::class, 'index'])->name('/');
Route::get('about', [PageController::class, 'about'])->name('about');
Route::get('doctors', [PageController::class, 'doctor'])->name('doctors');
Route::get('hospitals', [PageController::class, 'hospital'])->name('hospitals');
Route::get('blog', [PageController::class, 'blog'])->name('blog');
Route::get('detail', [PageController::class, 'detail'])->name('detail');
Route::get('team', [PageController::class, 'team'])->name('team');
Route::get('testimonial', [PageController::class, 'testimonial'])->name('testimonial');
Route::get('appointment', [PageController::class, 'appointment'])->name('appointment');
Route::get('search', [PageController::class, 'search'])->name('search');
Route::get('contact', [PageController::class, 'contact'])->name('contact');


// Login Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

//Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Admin Routes
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::any('/admin/hospital', [HospitalController::class, 'index'])->name('admin.hospital');
    Route::any('/admin/hospital/add', [HospitalController::class, 'add'])->name('admin.hospital.add');
    Route::any('/admin/hospital/delete', [HospitalController::class, 'delete'])->name('admin.hospital.delete');
});

// Doctor Routes
Route::middleware(['doctor'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorController::class, 'dashboard'])->name('doctor.dashboard');
});

// Auth Routes
Auth::routes();


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
