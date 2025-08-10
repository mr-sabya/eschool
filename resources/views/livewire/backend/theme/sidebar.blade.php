<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <!-- Single Menu Item -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect {{ Route::is('admin.dashboard') ? 'active' : '' }}">
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
                        <li><a href="{{ route('admin.class.index') }}" class="{{ Route::is('admin.class.index') ? 'active' : '' }}">Class</a></li>
                        <li><a href="{{ route('admin.section.index') }}" class="{{ Route::is('admin.section.index') ? 'active' : '' }}">Section</a></li>
                        <li><a href="{{ route('admin.shift.index') }}" class="{{ Route::is('admin.shift.index') ? 'active' : '' }}">Shift</a></li>
                        <li><a href="{{ route('admin.subject.index') }}" class="{{ Route::is('admin.subject.index') ? 'active' : '' }}">Subject</a></li>
                        <li><a href="{{ route('admin.class-subject-assign.index') }}" class="{{ Route::is('admin.class-subject-assign.index') ? 'active' : '' }}">Subject Assignments</a></li>
                        <li><a href="{{ route('admin.classroom.index') }}" class="{{ Route::is('admin.classroom.index') ? 'active' : '' }}">Classroom</a></li>
                        <li><a href="{{ route('admin.academic-session.index') }}" class="{{ Route::is('admin.academic-session.index') ? 'active' : '' }}">Academic Session</a></li>
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
                        <li><a href="{{ route('admin.designation.index') }}" class="{{ Route::is('admin.designation.index') ? 'active' : '' }}">Designation</a></li>
                        <li><a href="{{ route('admin.department.index') }}" class="{{ Route::is('admin.department.index') ? 'active' : '' }}">Department</a></li>
                        <li><a href="{{ route('admin.gender.index') }}" class="{{ Route::is('admin.gender.index') ? 'active' : '' }}">Gender</a></li>
                        <li><a href="{{ route('admin.blood.index') }}" class="{{ Route::is('admin.blood.index') ? 'active' : '' }}">Blood Group</a></li>
                        <li><a href="{{ route('admin.religion.index') }}" class="{{ Route::is('admin.religion.index') ? 'active' : '' }}">Religions</a></li>
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
                        <li><a href="{{ route('admin.student.index') }}" class="{{ Route::is('admin.student.index') ? 'active' : '' }}">Students List</a></li>
                        <li><a href="{{ route('admin.student.create') }}" class="{{ Route::is('admin.student.create') ? 'active' : '' }}">Add New Student</a></li>
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
                        <li><a href="{{ route('admin.guardian.index') }}" class="{{ Route::is('admin.guardian.index') ? 'active' : '' }}">Guardians List</a></li>
                        <li><a href="{{ route('admin.guardian.create') }}" class="{{ Route::is('admin.guardian.create') ? 'active' : '' }}">Add New Guardian</a></li>
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
                        <li><a href="{{ route('admin.staff.index') }}" class="{{ Route::is('admin.staff.index') ? 'active' : '' }}">Staff List</a></li>
                        <li><a href="{{ route('admin.staff.create') }}" class="{{ Route::is('admin.staff.create') ? 'active' : '' }}">Add New Staff</a></li>
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
                        <li><a href="{{ route('admin.exam-category.index') }}" class="{{ Route::is('admin.exam-category.index') ? 'active' : '' }}">Exam Categories</a></li>
                        <li><a href="{{ route('admin.exam.index') }}" class="{{ Route::is('admin.exam.index') ? 'active' : '' }}">Exams</a></li>
                        <li><a href="{{ route('admin.mark-distribution.index') }}" class="{{ Route::is('admin.mark-distribution.index') ? 'active' : '' }}">Mark Distribution</a></li>
                        <li><a href="{{ route('admin.subject-mark-distribution.index') }}" class="{{ Route::is('admin.subject-mark-distribution.index') ? 'active' : '' }}">Subject Mark Distribution</a></li>
                        <li><a href="{{ route('admin.final-mark-configuration.index') }}" class="{{ Route::is('admin.final-mark-configuration.index') ? 'active' : '' }}">Final Mark Configuration</a></li>
                        <li><a href="{{ route('admin.grade.index') }}" class="{{ Route::is('admin.grade.index') ? 'active' : '' }}">Grades</a></li>
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
                        <li><a href="{{ route('admin.student-mark.create') }}" class="{{ Route::is('admin.student-mark.create') ? 'active' : '' }}">Add Student Marks</a></li>
                        <li><a href="{{ route('admin.result.index') }}" class="{{ Route::is('admin.result.index') ? 'active' : '' }}">Student Results</a></li>
                        <li><a href="{{ route('admin.result.generate.pdf') }}" class="{{ Route::is('admin.result.generate.pdf') ? 'active' : '' }}">Generate PDF</a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>