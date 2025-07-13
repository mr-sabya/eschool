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

// login
Route::get('login', [App\Http\Controllers\Backend\Auth\LoginController::class, 'showLoginForm'])->name('login');

// home page
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Backend\HomeController::class, 'index'])->name('dashboard');

    // class management
    Route::get('classes', [App\Http\Controllers\Backend\SchoolClassController::class, 'index'])->name('class.index');

    // section management
    Route::get('sections', [App\Http\Controllers\Backend\ClassSectionController::class, 'index'])->name('section.index');

    // shift management
    Route::get('shifts', [App\Http\Controllers\Backend\ShiftController::class, 'index'])->name('shift.index');

    // subject management
    Route::get('subjects', [App\Http\Controllers\Backend\SubjectController::class, 'index'])->name('subject.index');
});
