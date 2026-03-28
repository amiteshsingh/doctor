<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Doctor\DoctorController as DoctorPanelController;
use App\Http\Controllers\Doctor\MyHospitalController;
use App\Http\Controllers\Doctor\InvoiceMasterController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Doctor\PrescriptionInvoiceController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PageController::class, 'index'])->name('/');
Route::get('about', [PageController::class, 'about'])->name('about');
Route::get('doctors', [PageController::class, 'doctor'])->name('doctors');
Route::get('hospitals', [PageController::class, 'hospital'])->name('hospitals');
Route::get('blog', [PageController::class, 'blog'])->name('blog');
Route::get('blog/{slug}', [PageController::class, 'blogDetail'])->name('blog-detail');
Route::get('detail', [PageController::class, 'detail'])->name('detail');
Route::get('team', [PageController::class, 'team'])->name('team');
Route::get('faq', [PageController::class, 'faq'])->name('faq');
Route::get('testimonial', [PageController::class, 'testimonial'])->name('testimonial');
Route::get('appointment', [PageController::class, 'appointment'])->name('appointment');
Route::get('search', [PageController::class, 'search'])->name('search');
Route::any('contact', [PageController::class, 'contact'])->name('contact');
Route::get('doctor-profile/{id}/{name}', [PageController::class, 'doctor_profile'])->name('doctors-profile');
Route::post('book-appointment', [PageController::class, 'bookAppointment'])->name('book.appointment');
Route::get('booked-slots', [PageController::class, 'bookedSlots'])->name('booked.slots');
Route::get('hospital-details/{id}/{name}', [PageController::class, 'hospital_details'])->name('hospital-details');
Route::get('/professional-doctors', [PageController::class, 'professionalDoctors'])->name('professional.doctors');
Route::get('/specializations/suggest', [PageController::class, 'specializationSuggest'])->name('specializations.suggest');
Route::get('terms', [PageController::class, 'terms'])->name('terms');
Route::get('disclaimer', [PageController::class, 'disclaimer'])->name('disclaimer');
Route::get('privacy-policy', [PageController::class, 'privacy_policy'])->name('privacy-policy');


Route::get('/test-mail', function() {
    try {
        Mail::raw('This is a test mail', function($message) {
            $message->to('rogisewa25@gmail.com')
                    ->subject('Test Mail');
        });
        return 'Mail sent successfully!';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});




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
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::any('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::any('/hospital', [HospitalController::class, 'index'])->name('admin.hospital');
    Route::any('/hospital/add', [HospitalController::class, 'add'])->name('admin.hospital.add');
    Route::any('/hospital/delete/{id}', [HospitalController::class, 'delete'])->name('admin.hospital.delete');
    Route::any('/hospital/hospital_specialization', [HospitalController::class, 'hospitalSpecializations'])->name('admin.hospital.hospital_specialization');

    Route::any('/specialization', [SpecializationController::class, 'index'])->name('admin.specialization');
    Route::any('/specialization/add', [SpecializationController::class, 'add'])->name('admin.specialization.add');
    Route::any('/specialization/delete/{id}', [SpecializationController::class, 'delete'])->name('admin.specialization.delete');

    Route::any('/doctor', [DoctorController::class, 'index'])->name('admin.doctor');
    Route::any('/doctor/add', [DoctorController::class, 'add'])->name('admin.doctor.add');
    Route::any('/doctor/delete/{id}', [DoctorController::class, 'delete'])->name('admin.doctor.delete');
    Route::any('/doctor/doctor_specialization', [DoctorController::class, 'doctorSpecializations'])->name('admin.doctor.hospital_specialization');
    Route::any('/doctor/doctor_location', [DoctorController::class, 'doctorLocation'])->name('admin.doctor.doctor_location');
    Route::post('/doctor/doctor_availability', [DoctorController::class, 'doctorAvailability'])->name('admin.doctor_availability');

    Route::any('/user', [UserController::class, 'index'])->name('admin.user');

    // Blog Routes
    Route::get('/blog', [\App\Http\Controllers\Admin\BlogController::class, 'index'])->name('admin.blog.index');
    Route::get('/blog/add', [\App\Http\Controllers\Admin\BlogController::class, 'add'])->name('admin.blog.add');
    Route::post('/blog/store', [\App\Http\Controllers\Admin\BlogController::class, 'store'])->name('admin.blog.store');
    Route::get('/blog/edit/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('admin.blog.edit');
    Route::put('/blog/update/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'update'])->name('admin.blog.update');
    Route::delete('/blog/delete/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'delete'])->name('admin.blog.delete');




});
// ------------------------------------------------------------------------------------
// Doctor Routes
// Doctor Routes
Route::middleware(['doctor'])->prefix('doctor')->group(function () {
    
    Route::get('/dashboard', [DoctorPanelController::class, 'dashboard'])->name('doctor.dashboard');
    Route::get('/edit-profile', [DoctorPanelController::class, 'editProfile'])->name('doctor.edit-profile');
    Route::post('/update-profile', [DoctorPanelController::class, 'update'])->name('doctor.update-profile');

    Route::any('/mydoctor', [DoctorPanelController::class, 'index'])->name('doctor.mydoctor');
    Route::any('/mydoctor/add', [DoctorPanelController::class, 'add'])->name('doctor.mydoctor.add');
    Route::any('/mydoctor/delete/{id}', [DoctorPanelController::class, 'delete'])->name('doctor.mydoctor.delete');
    Route::any('/mydoctor/doctor_specialization', [DoctorPanelController::class, 'doctorSpecializations'])->name('doctor.mydoctor.hospital_specialization');
    Route::any('/mydoctor/doctor_location', [DoctorPanelController::class, 'doctorLocation'])->name('doctor.mydoctor.doctor_location');
    Route::post('/mydoctor/doctor_availability', [DoctorPanelController::class, 'doctorAvailability'])->name('doctor.mydoctor.doctor_availability');
    Route::get('/mydoctor/profile/{id}', [DoctorPanelController::class, 'profile'])->name('doctor.mydoctor.profile');

    Route::any('/myhospital', [MyHospitalController::class, 'index'])->name('doctor.myhospital');
    Route::any('/myhospital/add', [MyHospitalController::class, 'add'])->name('doctor.myhospital.add');
    Route::any('/myhospital/delete/{id}', [MyHospitalController::class, 'delete'])->name('doctor.myhospital.delete');
    Route::any('/myhospital/hospital_specialization', [MyHospitalController::class, 'hospitalSpecializations'])->name('doctor.myhospital.hospital_specialization');

    Route::any('/invoice-master', [InvoiceMasterController::class, 'index'])->name('invoice-master.index');
    Route::any('/invoice-master/add', [InvoiceMasterController::class, 'add'])->name('invoice-master.add');
    Route::any('/invoice-master/delete/{id}', [InvoiceMasterController::class, 'delete'])->name('invoice-master.delete');

    Route::any('/prescription-invoice', [PrescriptionInvoiceController::class, 'index'])->name('prescription-invoice.index');
    Route::any('/prescription-invoice/add', [PrescriptionInvoiceController::class, 'add'])->name('prescription-invoice.add');
    Route::any('/prescription-invoice/delete/{id}', [PrescriptionInvoiceController::class, 'delete'])->name('prescription-invoice.delete');
    Route::get('prescription-invoice/pdf/{id}', [PrescriptionInvoiceController::class, 'generatePdf'])->name('prescription-invoice.pdf');




});


// Auth Routes
Auth::routes();


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
