<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Student Subject Attendance Report</h3>
        </div>
        <div class="card-body">
            {{-- Filter Controls --}}
            <div class="row mb-4 border-bottom pb-3">
                {{-- Academic Session Dropdown --}}
                <div class="col-md-3">
                    <label for="sessionId">Select Session</label>
                    <select wire:model="selectedAcademicSessionId" wire:change="handleAcademicSessionChange" class="form-control" id="sessionId">
                        <option value="">-- Choose Session --</option>
                        @foreach ($academicSessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- School Class Dropdown --}}
                <div class="col-md-3">
                    <label for="classId">Select Class</label>
                    <select wire:model="selectedSchoolClassId" wire:change="handleSchoolClassChange" class="form-control" id="classId" @if(!$selectedAcademicSessionId) disabled @endif>
                        <option value="">-- Choose Class --</option>
                        @foreach ($schoolClasses as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Class Section Dropdown --}}
                <div class="col-md-3">
                    <label for="sectionId">Select Section</label>
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
                        <option value="">-- All Departments --</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            <div class="row mb-4 border-bottom pb-3">
                {{-- Subject Dropdown --}}
                <div class="col-md-4">
                    <label for="subjectId">Select Subject</label>
                    <div wire:loading wire:target="handleFilterChange" class="text-muted"><small>Loading subjects...</small></div>
                    <select wire:model="selectedSubjectId" wire:change="handleReportGeneration" class="form-control" id="subjectId" wire:loading.remove @if(count($subjects)==0) disabled @endif>
                        <option value="">-- Choose Subject --</option>
                        @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Student Dropdown --}}
                <div class="col-md-4">
                    <label for="studentId">Select Student</label>
                    <div wire:loading wire:target="handleFilterChange" class="text-muted"><small>Loading students...</small></div>
                    <select wire:model="selectedStudentId" wire:change="handleReportGeneration" class="form-control" id="studentId" wire:loading.remove @if(count($students)==0) disabled @endif>
                        <option value="">-- Choose Student --</option>
                        @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->user['name'] }} (ID: {{ $student->roll_number }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Month Selector --}}
                <div class="col-md-4">
                    <label for="month">Select Month</label>
                    <input type="month" wire:model="month" wire:change="handleReportGeneration" class="form-control" id="month">
                </div>
            </div>

            {{-- Report Display Area --}}
            <div wire:loading wire:target="handleReportGeneration" class="text-center my-4">
                <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                <p>Generating Report...</p>
            </div>

            <div wire:loading.remove>
                @if ($selectedStudent && $selectedSubject)
                <div class="row mb-4">
                    <div class="col-12">
                        <h4>Report for: <span class="text-primary">{{ $selectedStudent->user_name }}</span></h4>
                        <p>
                            <strong>Class:</strong> {{ $selectedStudent->schoolClass->name }} - {{ $selectedStudent->classSection->name }} <br>
                            <strong>Subject:</strong> {{ $selectedSubject->name }}
                        </p>
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
                                <td colspan="2" class="text-center">No attendance data available for the selected criteria.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted mt-4">
                    <p>Please make all selections to view the report.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>