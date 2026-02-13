<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');

// teacher list
Route::get('/teachers', [App\Http\Controllers\Frontend\StaffController::class, 'teacherList'])->name('teacher.index');


// login routes
Route::get('/login', [App\Http\Controllers\Frontend\AuthController::class, 'showLoginForm'])->name('login');

// student list by class
Route::get('/students/class/{numeric}', [App\Http\Controllers\Frontend\StudentController::class, 'index'])->name('student.index');

// admission info
Route::get('/admission/info', [App\Http\Controllers\Frontend\AdmissionController::class, 'info'])->name('admission.info');

// admission form
Route::get('/admission/form', [App\Http\Controllers\Frontend\AdmissionController::class, 'form'])->name('admission.form');

// photo gallery
Route::get('/media/photos', [App\Http\Controllers\Frontend\MediaController::class, 'photoGallery'])->name('media.photos');
// video gallery
Route::get('/media/videos', [App\Http\Controllers\Frontend\MediaController::class, 'videoGallery'])->name('media.videos');

// governing body
Route::get('/governing-body', [App\Http\Controllers\Frontend\WebsiteController::class, 'governingBody'])->name('governing-body.index');

// staff
Route::get('/staff', [App\Http\Controllers\Frontend\WebsiteController::class, 'staff'])->name('staff.index');

// former-headmaster
Route::get('/former-headmaster', [App\Http\Controllers\Frontend\WebsiteController::class, 'formerHeadmaster'])->name('former-headmaster.index');

// profile
// auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/student/profile', [App\Http\Controllers\Frontend\Student\ProfileController::class, 'index'])->name('student.profile.index');
});
