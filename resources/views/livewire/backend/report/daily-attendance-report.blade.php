<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Student Daily Attendance Report</h3>
        </div>
        <div class="card-body">
            {{-- Filter Controls --}}
            <div class="row mb-4 border-bottom pb-3">
                {{-- School Class Dropdown --}}
                <div class="col-md-3">
                    <label for="classId">Select Class</label>
                    {{-- ADDED wire:change to call the custom method --}}
                    <select wire:model="selectedSchoolClassId" wire:change="handleSchoolClassChange" class="form-control" id="classId">
                        <option value="">-- Choose Class --</option>
                        @foreach ($schoolClasses as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Class Section Dropdown --}}
                <div class="col-md-3">
                    <label for="sectionId">Select Section</label>
                    {{-- ADDED wire:change --}}
                    <select wire:model="selectedClassSectionId" wire:change="handleFilterChange" class="form-control" id="sectionId" @if(!$selectedSchoolClassId) disabled @endif>
                        <option value="">-- Choose Section --</option>
                        @foreach ($classSections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Department Dropdown (Conditional) --}}
                @if($showDepartmentFilter)
                <div class="col-md-3">
                    <label for="departmentId">Select Department</label>
                    <select wire:model="selectedDepartmentId" wire:change="handleFilterChange" class="form-control" id="departmentId" @if(!$selectedClassSectionId) disabled @endif>
                        {{-- This option allows users to see students from all departments within the selected class/section --}}
                        <option value="">-- All Departments --</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Month Selector --}}
                <div class="col-md-3">
                    <label for="month">Select Month</label>
                    {{-- ADDED wire:change --}}
                    <input type="month" wire:model="month" wire:change="handleReportGeneration" class="form-control" id="month">
                </div>
            </div>

            <div class="row mb-4 border-bottom pb-3">
                {{-- Student Dropdown --}}
                <div class="col-md-6">
                    <label for="studentId">Select Student</label>
                    <div wire:loading wire:target="selectedClassSectionId, selectedDepartmentId" class="text-muted">
                        <small>Loading students...</small>
                    </div>
                    {{-- ADDED wire:change --}}
                    <select wire:model="selectedStudentId" wire:change="handleReportGeneration" class="form-control" id="studentId" wire:loading.remove @if(!$selectedClassSectionId) disabled @endif>
                        <option value="">-- Choose Student --</option>
                        @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->user['name'] }} (ID: {{ $student->roll_number }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Report Display Area (No changes needed below this line) --}}
            <div wire:loading wire:target="selectedStudentId, month" class="text-center my-4">
                <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                <p>Generating Report...</p>
            </div>

            <div wire:loading.remove>
                @if ($selectedStudent)
                <div class="row mb-4">
                    <div class="col-12">
                        <h4>Report for: <span class="text-primary">{{ $selectedStudent->user_name }}</span></h4>
                        <p><strong>Class:</strong> {{ $selectedStudent->schoolClass->name }} - {{ $selectedStudent->classSection->name }}</p>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <div class="info-box bg-success p-3">
                            <div class="info-box-content text-white"><span class="info-box-text">Present </span><span class="info-box-number">{{ $summary['present'] }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <div class="info-box bg-danger p-3">
                            <div class="info-box-content text-white"><span class="info-box-text">Absent </span><span class="info-box-number">{{ $summary['absent'] }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <div class="info-box bg-warning p-3">
                            <div class="info-box-content text-white"><span class="info-box-text">Late </span><span class="info-box-number">{{ $summary['late'] }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <div class="info-box bg-info p-3">
                            <div class="info-box-content text-white"><span class="info-box-text">Holiday </span><span class="info-box-number">{{ $summary['holiday'] }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <div class="info-box bg-secondary p-3">
                            <div class="info-box-content text-white"><span class="info-box-text">Half Day </span><span class="info-box-number">{{ $summary['half_day'] }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <div class="info-box bg-primary p-3">
                            <div class="info-box-content text-white"><span class="info-box-text">Percentage </span><span class="info-box-number">{{ $summary['percentage'] }}%</span></div>
                        </div>
                    </div>
                </div>

                <h5>Daily Status for {{ \Carbon\Carbon::parse($month)->format('F, Y') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendanceData as $day => $status)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($month)->startOfMonth()->addDays($day - 1)->format('d F, Y (l)') }}</td>
                                <td>
                                    @php
                                    $badgeClass = match (strtolower($status)) {
                                    'present' => 'badge badge-success', 'absent' => 'badge badge-danger',
                                    'late' => 'badge badge-warning', 'holiday' => 'badge badge-info',
                                    'half day' => 'badge badge-secondary', default => 'badge badge-light',
                                    };
                                    @endphp
                                    <span class="{{ $badgeClass }} text-dark">{{ $status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">No attendance data available for this month.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted mt-4">
                    <p>Please select a class, section, and student to view their attendance report.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>