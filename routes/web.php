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

// profile
// auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/student/profile', [App\Http\Controllers\Frontend\Student\ProfileController::class, 'index'])->name('student.profile.index');
});
