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

    // academic session management
    Route::get('academic-sessions', [App\Http\Controllers\Backend\AcademicSessionController::class, 'index'])->name('academic-session.index');

    // subject management
    Route::get('subjects', [App\Http\Controllers\Backend\SubjectController::class, 'index'])->name('subject.index');

    // class subject assign management
    Route::get('class-subject-assigns', [App\Http\Controllers\Backend\ClassSubjectAssignController::class, 'index'])->name('class-subject-assign.index');

    // class subject assign create
    Route::get('class-subject-assigns/create', [App\Http\Controllers\Backend\ClassSubjectAssignController::class, 'create'])->name('class-subject-assign.create');

    // class subject assign edit
    Route::get('class-subject-assigns/{classSubjectAssignId}/edit', [App\Http\Controllers\Backend\ClassSubjectAssignController::class, 'edit'])->name('class-subject-assign.edit');

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
    Route::get('students/edit/{id}', [App\Http\Controllers\Backend\StudentController::class, 'edit'])->name('student.edit');
    
    // admit card
    Route::get('students/admit-card', [App\Http\Controllers\Backend\StudentController::class, 'admitCardGenerate'])->name('student.admit-card.index');
    Route::get('/students/admit-card/generate', [App\Http\Controllers\Backend\AdmitCardController::class, 'generate'])->name('student.admit-card.generate');


    Route::get('/students/id-card', [App\Http\Controllers\Backend\StudentController::class, 'stduentIdCard'])->name('student.id-card');


    // import students
    Route::get('students/import', [App\Http\Controllers\Backend\StudentController::class, 'import'])->name('student.import');

    // download sample import file
    Route::get('students/download-sample', [App\Http\Controllers\Backend\StudentController::class, 'download'])->name('student.download.sample');

    // guardian management
    Route::get('guardians', [App\Http\Controllers\Backend\GuardianController::class, 'index'])->name('guardian.index');
    Route::get('guardians/create', [App\Http\Controllers\Backend\GuardianController::class, 'create'])->name('guardian.create');
    Route::get('guardians/{id}/edit', [App\Http\Controllers\Backend\GuardianController::class, 'edit'])->name('guardian.edit');

    // staff management
    Route::get('staff', [App\Http\Controllers\Backend\StaffController::class, 'index'])->name('staff.index');
    Route::get('staff/create', [App\Http\Controllers\Backend\StaffController::class, 'create'])->name('staff.create');
    Route::get('staff/{id}/edit', [App\Http\Controllers\Backend\StaffController::class, 'edit'])->name('staff.edit');

    // import staff
    Route::get('staff/import', [App\Http\Controllers\Backend\StaffController::class, 'import'])->name('staff.import');

    // download staff import template
    Route::get('staff/download-template', [App\Http\Controllers\Backend\StaffController::class, 'download'])->name('staff.download.template');

    // exam category management
    Route::get('exam-categories', [App\Http\Controllers\Backend\ExamCategoryController::class, 'index'])->name('exam-category.index');

    // exam management
    Route::get('exams', [App\Http\Controllers\Backend\ExamController::class, 'index'])->name('exam.index');

    // mark distribution management
    Route::get('mark-distributions', [App\Http\Controllers\Backend\MarkDistributionController::class, 'index'])->name('mark-distribution.index');

    // subject mark distribution management
    Route::get('subject-mark-distributions', [App\Http\Controllers\Backend\SubjectMarkDistributionController::class, 'index'])->name('subject-mark-distribution.index');

    // subject mark distribution create
    Route::get('subject-mark-distributions/create', [App\Http\Controllers\Backend\SubjectMarkDistributionController::class, 'create'])->name('subject-mark-distribution.create');

    // subject mark distribution edit
    Route::get('subject-mark-distributions/{id}/edit', [App\Http\Controllers\Backend\SubjectMarkDistributionController::class, 'edit'])->name('subject-mark-distribution.edit');


    // final mark configuration
    Route::get('final-mark-configuration', [App\Http\Controllers\Backend\FinalMarkConfigurationController::class, 'index'])->name('final-mark-configuration.index');

    // final mark configuration create
    Route::get('final-mark-configuration/create', [App\Http\Controllers\Backend\FinalMarkConfigurationController::class, 'create'])->name('final-mark-configuration.create');

    // final mark configuration edit
    Route::get('final-mark-configuration/{id}/edit', [App\Http\Controllers\Backend\FinalMarkConfigurationController::class, 'edit'])->name('final-mark-configuration.edit');

    // grade management
    Route::get('grades', [App\Http\Controllers\Backend\GradeController::class, 'index'])->name('grade.index');

    // student mark management
    Route::get('student-marks', [App\Http\Controllers\Backend\StudentMarkController::class, 'index'])->name('student-mark.index');
    Route::get('student-marks/create', [App\Http\Controllers\Backend\StudentMarkController::class, 'create'])->name('student-mark.create');

    // result management
    Route::get('results', [App\Http\Controllers\Backend\ResultController::class, 'index'])->name('result.index');

    // show result
    Route::get('results/{studentId}/{examId}/{classId}/{sectionId}/{sessionId}', [App\Http\Controllers\Backend\ResultController::class, 'show'])
        ->name('result.show');


    // generate PDF
    Route::get('results/generate-pdf', [App\Http\Controllers\Backend\ResultController::class, 'generatePdf'])
        ->name('result.generate.pdf');

    // download result PDF
    Route::get('results/download/{studentId}/{examId}', [App\Http\Controllers\Backend\ResultController::class, 'download'])
        ->name('result.download');

    // setting
    Route::get('settings', [App\Http\Controllers\Backend\SettingController::class, 'index'])
        ->name('setting.index');

    // show result
    Route::get('show-result-position', [App\Http\Controllers\Backend\ResultController::class, 'showResultPosition'])
        ->name('result.position.show');

        // tabulation index
    Route::get('tabulation-sheet/index', [App\Http\Controllers\Backend\ResultController::class, 'tabulationIndex'])
        ->name('result.tabulation.index');

    // tabulation sheet
    Route::get('tabulation-sheet', [App\Http\Controllers\Backend\ResultController::class, 'tabulationSheet'])
        ->name('result.tabulation.sheet');  

    
    // subject attendance
    Route::get('subject-attendance/manage', [App\Http\Controllers\Backend\AttendanceController::class, 'subjectAttendaceManage'])->name('subject-attendance.manage');

    // daily attendance
    Route::get('daily-attendance/manage', [App\Http\Controllers\Backend\AttendanceController::class, 'dailyAttendaceManage'])->name('daily-attendance.manage');

    // leave type
    Route::get('leave-types', [App\Http\Controllers\Backend\LeaveController::class, 'leaveType'])->name('leave.type.index');

    // student leave
    Route::get('student-leaves', [App\Http\Controllers\Backend\LeaveController::class, 'studentLeave'])->name('leave.student.index');


    // fee type management
    Route::get('fee/types', [App\Http\Controllers\Backend\FeeController::class, 'feeType'])->name('fee.type.index');

    // fee list management
    Route::get('fee/lists', [App\Http\Controllers\Backend\FeeController::class, 'feeList'])->name('fee.list.index');
    
    // fee collection
    Route::get('fee/collection', [App\Http\Controllers\Backend\FeeController::class, 'feeCollectionIndex'])->name('fee.collection.index');
    Route::get('fee/collection/create', [App\Http\Controllers\Backend\FeeController::class, 'feeCollectionCreate'])->name('fee.collection.create');


    // library book category
    Route::get('library/book-categories', [App\Http\Controllers\Backend\LibraryController::class, 'bookCategory'])->name('library.book-category.index');

    // library book
    Route::get('library/books', [App\Http\Controllers\Backend\LibraryController::class, 'book'])->name('library.book.index');
    Route::get('library/books/create', [App\Http\Controllers\Backend\LibraryController::class, 'createBook'])->name('library.book.create');

    // library member category
    Route::get('library/member-categories', [App\Http\Controllers\Backend\LibraryController::class, 'memberCategory'])->name('library.member-category.index');

    // library member
    Route::get('library/members', [App\Http\Controllers\Backend\LibraryController::class, 'member'])->name('library.member.index');
    Route::get('library/members/create', [App\Http\Controllers\Backend\LibraryController::class, 'createMember'])->name('library.member.create');

    // library book issue
    Route::get('library/book-issues', [App\Http\Controllers\Backend\LibraryController::class, 'bookIssue'])->name('library.book-issue.index');
    Route::get('library/book-issues/create', [App\Http\Controllers\Backend\LibraryController::class, 'createBookIssue'])->name('library.book-issue.create');

    // income heads
    Route::get('accounts/income-heads', [App\Http\Controllers\Backend\IncomeController::class, 'incomeHeads'])->name('accounts.income-head.index');

    // income
    Route::get('accounts/income', [App\Http\Controllers\Backend\IncomeController::class, 'income'])->name('accounts.income.index');

    // expense heads
    Route::get('accounts/expense-heads', [App\Http\Controllers\Backend\ExpenseController::class, 'expenseHead'])->name('accounts.expense-head.index');

    // expense
    Route::get('accounts/expense', [App\Http\Controllers\Backend\ExpenseController::class, 'expense'])->name('accounts.expense.index');

    // website banner
    Route::get('website/banners', [App\Http\Controllers\Backend\Website\BannerController::class, 'index'])->name('website.banner.index');
    Route::get('website/banners/create', [App\Http\Controllers\Backend\Website\BannerController::class, 'create'])->name('website.banner.create');
    Route::get('website/banners/{id}/edit', [App\Http\Controllers\Backend\Website\BannerController::class, 'edit'])->name('website.banner.edit');

    // notice board
    Route::get('website/notices', [App\Http\Controllers\Backend\Website\NoticeController::class, 'index'])->name('website.notice.index');
    Route::get('website/notices/create', [App\Http\Controllers\Backend\Website\NoticeController::class, 'create'])->name('website.notice.create');
    Route::get('website/notices/{id}/edit', [App\Http\Controllers\Backend\Website\NoticeController::class, 'edit'])->name('website.notice.edit');

    // routine - day
    Route::get('routine/days', [App\Http\Controllers\Backend\RoutineController::class, 'day'])->name('routine.day.index');

    // routine - time slot
    Route::get('routine/time-slots', [App\Http\Controllers\Backend\RoutineController::class, 'timeSlot'])->name('routine.time-slot.index');

    // routine - routine
    Route::get('routine', [App\Http\Controllers\Backend\RoutineController::class, 'index'])->name('routine.index');

    // routine - exam routine
    Route::get('routine/exam-routine', [App\Http\Controllers\Backend\RoutineController::class, 'examRoutine'])->name('routine.exam-routine.index');

    // notifications
    Route::get('notifications', [App\Http\Controllers\Backend\NotificationController::class, 'index'])->name('notification.index');
    
    // reports
    Route::get('/reports/financial', [App\Http\Controllers\Backend\ReportController::class, 'finalcialReports'])->name('reports.financial');

    // fee collection
    Route::get('/reports/fee-collection', [App\Http\Controllers\Backend\ReportController::class, 'feeCollectionReports'])->name('reports.fee-collection');

    // daily attendance
    Route::get('/reports/daily-attendance', [App\Http\Controllers\Backend\ReportController::class, 'dailyAttendaceReports'])->name('reports.daily-attendance');

    // 
    Route::get('/reports/subject-attendance', [App\Http\Controllers\Backend\ReportController::class, 'subjectAttendaceReports'])->name('reports.subject-attendance');

});
