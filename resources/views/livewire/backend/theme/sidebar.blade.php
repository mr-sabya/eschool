<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <!-- Single Menu Item -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect {{ Route::is('admin.dashboard') ? 'active' : '' }}" wire:navigate>
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Academic -->
                <li class="{{ Route::is('admin.class.index', 'admin.section.index', 'admin.shift.index', 'admin.subject.index', 'admin.class-subject-assign.*', 'admin.classroom.index', 'admin.academic-session.index') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.class.index', 'admin.section.index', 'admin.shift.index', 'admin.subject.index', 'admin.subject-assign.index', 'admin.classroom.index', 'admin.academic-session.index') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Academic</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.class.index') }}" class="{{ Route::is('admin.class.index') ? 'active' : '' }}" wire:navigate>Class</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.section.index') }}" class="{{ Route::is('admin.section.index') ? 'active' : '' }}" wire:navigate>Section</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.shift.index') }}" class="{{ Route::is('admin.shift.index') ? 'active' : '' }}" wire:navigate>Shift</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.subject.index') }}" class="{{ Route::is('admin.subject.index') ? 'active' : '' }}" wire:navigate>Subject</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.class-subject-assign.index') }}" class="{{ Route::is('admin.class-subject-assign.index') ? 'active' : '' }}" wire:navigate>Subject Assignments</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.classroom.index') }}" class="{{ Route::is('admin.classroom.index') ? 'active' : '' }}" wire:navigate>Classroom</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.academic-session.index') }}" class="{{ Route::is('admin.academic-session.index') ? 'active' : '' }}" wire:navigate>Academic Session</a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Dropdown Menu: App Setting -->
                @if(Auth::check() && Auth::user()->isAdmin())
                <li class="{{ Route::is('admin.designation.index', 'admin.department.index', 'admin.gender.index', 'admin.blood.index', 'admin.religion.index') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.designation.index', 'admin.department.index', 'admin.gender.index', 'admin.blood.index', 'admin.religion.index') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>App Setting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.designation.index') }}" class="{{ Route::is('admin.designation.index') ? 'active' : '' }}" wire:navigate>Designation</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.department.index') }}" class="{{ Route::is('admin.department.index') ? 'active' : '' }}" wire:navigate>Department</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.gender.index') }}" class="{{ Route::is('admin.gender.index') ? 'active' : '' }}" wire:navigate>Gender</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.blood.index') }}" class="{{ Route::is('admin.blood.index') ? 'active' : '' }}" wire:navigate>Blood Group</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.religion.index') }}" class="{{ Route::is('admin.religion.index') ? 'active' : '' }}" wire:navigate>Religions</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Students -->
                <li class="{{ Route::is('admin.student.*') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.student.*') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Students</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.student.index') }}" class="{{ Route::is('admin.student.index') ? 'active' : '' }}" wire:navigate>Students List</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.student.create') }}" class="{{ Route::is('admin.student.create') ? 'active' : '' }}" wire:navigate>Add New Student</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.student.admit-card.index') }}" class="{{ Route::is('admin.student.admit-card.index') ? 'active' : '' }}" wire:navigate>Generate Admit Card</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Guardians -->
                <li class="{{ Route::is('admin.guardian.*') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.guardian.*') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Guardians</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.guardian.index') }}" class="{{ Route::is('admin.guardian.index') ? 'active' : '' }}" wire:navigate>Guardians List</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.guardian.create') }}" class="{{ Route::is('admin.guardian.create') ? 'active' : '' }}" wire:navigate>Add New Guardian</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Staffs -->
                <li class="{{ Route::is('admin.staff.*') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.staff.*') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Staffs</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.staff.index') }}" class="{{ Route::is('admin.staff.index') ? 'active' : '' }}" wire:navigate>Staff List</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.staff.create') }}" class="{{ Route::is('admin.staff.create') ? 'active' : '' }}" wire:navigate>Add New Staff</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Exams -->
                <li class="{{ Route::is('admin.exam-category.index', 'admin.exam.index', 'admin.mark-distribution.index', 'admin.subject-mark-distribution.*', 'admin.final-mark-configuration.*', 'admin.grade.index') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.exam-category.index', 'admin.exam.index', 'admin.mark-distribution.index', 'admin.subject-mark-distribution.index', 'admin.final-mark-configuration.index', 'admin.grade.index') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Exams</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.exam-category.index') }}" class="{{ Route::is('admin.exam-category.index') ? 'active' : '' }}" wire:navigate>Exam Categories</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.exam.index') }}" class="{{ Route::is('admin.exam.index') ? 'active' : '' }}" wire:navigate>Exams</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.mark-distribution.index') }}" class="{{ Route::is('admin.mark-distribution.index') ? 'active' : '' }}" wire:navigate>Mark Distribution</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.subject-mark-distribution.index') }}" class="{{ Route::is('admin.subject-mark-distribution.index') ? 'active' : '' }}" wire:navigate>Subject Mark Distribution</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.final-mark-configuration.index') }}" class="{{ Route::is('admin.final-mark-configuration.index') ? 'active' : '' }}" wire:navigate>Final Mark Configuration</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.grade.index') }}" class="{{ Route::is('admin.grade.index') ? 'active' : '' }}" wire:navigate>Grades</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(Auth::check() && Auth::user()->isAdmin() || Auth::user()->isTeacher())
                <!-- Dropdown Menu: Marks -->
                <li class="{{ Route::is('admin.student-mark.create', 'admin.result.index', 'admin.result.generate.pdf') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.student-mark.create', 'admin.result.index', 'admin.result.generate.pdf') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Marks</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.student-mark.create') }}" class="{{ Route::is('admin.student-mark.create') ? 'active' : '' }}">Add Student Marks</a>
                        </li>
                        @if(Auth::check() && Auth::user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.result.index') }}" class="{{ Route::is('admin.result.index') ? 'active' : '' }}">Student Results</a>
                        </li>
                        @endif
                        <!-- <li><a href="{{ route('admin.result.generate.pdf') }}" class="{{ Route::is('admin.result.generate.pdf') ? 'active' : '' }}">Generate PDF</a></li> -->

                        
                    </ul>
                </li>
                @endif

                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Marks -->
                <li class="{{ Route::is('admin.website.banner.*', 'admin.website.notice.*') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.website.banner.*', 'admin.website.notice.*') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Website</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.website.banner.index') }}" class="{{ Route::is('admin.website.banner.index') ? 'active' : '' }}" wire:navigate>Banners</a>
                        </li>

                        <li>
                            <a href="{{ route('admin.website.notice.index') }}" class="{{ Route::is('admin.website.notice.index') ? 'active' : '' }}" wire:navigate>Notice Board</a>
                        </li>

                    </ul>
                </li>
                @endif


                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Marks -->
                <li class="{{ Route::is('admin.subject-attendance.*', 'admin.daily-attendance.*') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.subject-attendance.*', 'admin.daily-attendance.*') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Attendance</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.subject-attendance.manage') }}" class="{{ Route::is('admin.subject-attendance.manage') ? 'active' : '' }}" wire:navigate>Subject Attendance</a>
                        </li>

                        <li>
                            <a href="{{ route('admin.daily-attendance.manage') }}" class="{{ Route::is('admin.daily-attendance.manage') ? 'active' : '' }}" wire:navigate>Daily Attendance</a>
                        </li>

                    </ul>
                </li>
                @endif

                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Marks -->
                <li class="{{ Route::is('admin.leave.type.index', 'admin.leave.student.index') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.leave.type.index', 'admin.leave.student.index') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Leave</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.leave.type.index') }}" class="{{ Route::is('admin.leave.type.index') ? 'active' : '' }}" wire:navigate>Leave Type</a>
                        </li>

                        <li>
                            <a href="{{ route('admin.leave.student.index') }}" class="{{ Route::is('admin.leave.student.index') ? 'active' : '' }}" wire:navigate>Student Leave</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Marks -->
                <li class="{{ Route::is('admin.fee.type.index', 'admin.fee.list.index', 'admin.fee.collection.*') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.fee.type.index', 'admin.fee.list.index', 'admin.fee.collection.*') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Fee</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.fee.type.index') }}" class="{{ Route::is('admin.fee.type.index') ? 'active' : '' }}" wire:navigate>Fee Type</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.fee.list.index') }}" class="{{ Route::is('admin.fee.list.index') ? 'active' : '' }}" wire:navigate>Fee List</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.fee.collection.index') }}" class="{{ Route::is('admin.fee.collection.index') ? 'active' : '' }}" wire:navigate>Fee Collection</a>
                        </li>

                    </ul>
                </li>
                @endif

                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Marks -->
                <li class="{{ Route::is('admin.library.book-category.index', 'admin.library.book.*', 'admin.library.member-category.index', 'admin.library.member.*', 'admin.library.book-issue.*') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.library.book-category.index', 'admin.library.book.*', 'admin.library.member-category.index', 'admin.library.member.*', 'admin.library.book-issue.*') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Library</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.library.book-category.index') }}" class="{{ Route::is('admin.library.book-category.index') ? 'active' : '' }}" wire:navigate>Book Categories</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.library.book.index') }}" class="{{ Route::is('admin.library.book.index') ? 'active' : '' }}" wire:navigate>Books</a>
                        </li>

                        <li>
                            <a href="{{ route('admin.library.member-category.index') }}" class="{{ Route::is('admin.library.member-category.index') ? 'active' : '' }}" wire:navigate>Member Categories</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.library.member.index') }}" class="{{ Route::is('admin.library.member.index') ? 'active' : '' }}" wire:navigate>Members</a>
                        </li>

                        <li>
                            <a href="{{ route('admin.library.book-issue.index') }}" class="{{ Route::is('admin.library.book-issue.index') ? 'active' : '' }}" wire:navigate>Book Issues</a>
                        </li>

                    </ul>
                </li>
                @endif

                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Marks -->
                <li class="{{ Route::is('admin.accounts.income-head.index', 'admin.accounts.income.index', 'admin.accounts.expense-head.index', 'admin.accounts.expense.index') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.accounts.income-head.index', 'admin.accounts.income.index', 'admin.accounts.expense-head.index', 'admin.accounts.expense.index') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Income & Expense</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.accounts.income-head.index') }}" class="{{ Route::is('admin.accounts.income-head.index') ? 'active' : '' }}" wire:navigate>Income Heads</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.accounts.income.index') }}" class="{{ Route::is('admin.accounts.income.index') ? 'active' : '' }}" wire:navigate>Income</a>
                        </li>

                        <li>
                            <a href="{{ route('admin.accounts.expense-head.index') }}" class="{{ Route::is('admin.accounts.expense-head.index') ? 'active' : '' }}" wire:navigate>Expense Heads</a>
                        </li>

                        <li>
                            <a href="{{ route('admin.accounts.expense.index') }}" class="{{ Route::is('admin.accounts.expense.index') ? 'active' : '' }}" wire:navigate>Expense</a>
                        </li>

                    </ul>
                </li>
                @endif
                @if(Auth::check() && Auth::user()->isAdmin())
                <!-- Dropdown Menu: Marks -->
                <li class="{{ Route::is('admin.routine.day.index', 'admin.routine.time-slot.index', 'admin.routine.index', 'admin.routine.exam-routine.index') ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow waves-effect {{ Route::is('admin.routine.day.index', 'admin.routine.time-slot.index', 'admin.routine.index', 'admin.routine.exam-routine.index') ? 'active' : '' }}">
                        <i class="ri-wallet-line"></i>
                        <span>Routine</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.routine.day.index') }}" class="{{ Route::is('admin.routine.day.index') ? 'active' : '' }}" wire:navigate>Days</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.routine.time-slot.index') }}" class="{{ Route::is('admin.routine.time-slot.index') ? 'active' : '' }}" wire:navigate>Time Slots</a>
                        </li>

                        <li>
                            <a href="{{ route('admin.routine.index') }}" class="{{ Route::is('admin.routine.index') ? 'active' : '' }}" wire:navigate>Class Routine</a>
                        </li>

                        <li>
                            <a href="{{ route('admin.routine.exam-routine.index') }}" class="{{ Route::is('admin.routine.exam-routine.index') ? 'active' : '' }}" wire:navigate>Exam Routine</a>
                        </li>

                    </ul>
                </li>
                @endif

                <!-- setting -->
                <li class="{{ Route::is('admin.setting.index') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.setting.index') }}" class="waves-effect {{ Route::is('admin.setting.index') ? 'active' : '' }}" wire:navigate>
                        <i class="ri-settings-2-line"></i>
                        <span>Settings</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>