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

    // classroom management
    Route::get('classrooms', [App\Http\Controllers\Backend\ClassRoomController::class, 'index'])->name('classroom.index');

    // section management
    Route::get('sections', [App\Http\Controllers\Backend\ClassSectionController::class, 'index'])->name('section.index');

    // shift management
    Route::get('shifts', [App\Http\Controllers\Backend\ShiftController::class, 'index'])->name('shift.index');

    // subject management
    Route::get('subjects', [App\Http\Controllers\Backend\SubjectController::class, 'index'])->name('subject.index');

    // designation management
    Route::get('designations', [App\Http\Controllers\Backend\DesignationController::class, 'index'])->name('designation.index');

    // department management
    Route::get('departments', [App\Http\Controllers\Backend\DepartmentController::class, 'index'])->name('department.index');

    // gender management
    Route::get('gender', [App\Http\Controllers\Backend\GenderController::class, 'index'])->name('gender.index');

    // blood group management
    Route::get('blood-group', [App\Http\Controllers\Backend\BloodController::class, 'index'])->name('blood.index');

    // religion management
    Route::get('religion', [App\Http\Controllers\Backend\ReligionController::class, 'index'])->name('religion.index');

    // student management
    Route::get('students', [App\Http\Controllers\Backend\StudentController::class, 'index'])->name('student.index');
    Route::get('students/create', [App\Http\Controllers\Backend\StudentController::class, 'create'])->name('student.create');

    // guardian management
    Route::get('guardians', [App\Http\Controllers\Backend\GuardianController::class, 'index'])->name('guardian.index');
    Route::get('guardians/create', [App\Http\Controllers\Backend\GuardianController::class, 'create'])->name('guardian.create');
    Route::get('guardians/{id}/edit', [App\Http\Controllers\Backend\GuardianController::class, 'edit'])->name('guardian.edit');

    // staff management
    Route::get('staff', [App\Http\Controllers\Backend\StaffController::class, 'index'])->name('staff.index');
    Route::get('staff/create', [App\Http\Controllers\Backend\StaffController::class, 'create'])->name('staff.create');
    Route::get('staff/{id}/edit', [App\Http\Controllers\Backend\StaffController::class, 'edit'])->name('staff.edit');
});
