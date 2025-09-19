<div>
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Generate Result</div>
        <div class="card-body">
            <form wire:submit.prevent="generateResults" class="row g-3 align-items-center">
                <div class="col-md-3">
                    <label for="session" class="form-label">Academic Session</label>
                    <select wire:model="selectedSession" id="session" class="form-select" wire:change="loadExamsForSession" required>
                        <option value="">Select Session</option>
                        @foreach($academicSessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="class" class="form-label">Class</label>
                    <select wire:model="selectedClass" id="class" class="form-select" wire:change="loadSectionsForClass" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="section" class="form-label">Section</label>
                    <select wire:model="selectedSection" id="section" class="form-select" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="department" class="form-label">Department</label>
                    <select wire:model="selectedDepartment" id="department" class="form-select">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="exam" class="form-label">Exam</label>
                    <select wire:model="selectedExam" id="exam" class="form-select" required>
                        <option value="">Select Exam</option>
                        @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">
                            {{ $exam->examCategory->name ?? 'Exam' }} ({{ \Carbon\Carbon::parse($exam->start_at)->format('d M, Y') }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>

    @if($students->count())

    {{-- vvv ADD THIS BUTTON vvv --}}
    <div class="card">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h4 class="text-white">Student List ({{ $students->count() }})</h4>

            <div class="d-flex">

                <button wire:click="downloadMeritListPdf" wire:loading.attr="disabled" class="btn btn-light me-2">
                    <span wire:loading wire:target="downloadMeritListPdf" class="spinner-border spinner-border-sm"></span>
                    Download Merit List
                </button>

                <button wire:click="downloadSummaryPdf" wire:loading.attr="disabled" class="btn btn-light me-2">
                    <span wire:loading wire:target="downloadSummaryPdf" class="spinner-border spinner-border-sm"></span>
                    Download Summary
                </button>

                {{-- ADD THIS NEW BUTTON --}}
                <button wire:click="downloadTabulationSheetPdf" class="btn btn-light me-2">
                    <span wire:loading wire:target="downloadTabulationSheetPdf" class="spinner-border spinner-border-sm"></span>
                    <i class="fas fa-table"></i> Download Tabulation Sheet
                </button>

                <button wire:click="downloadAllPdfs" wire:loading.attr="disabled" class="btn btn-light">
                    {{-- Show spinner only while dispatching the job --}}
                    <span wire:loading wire:target="downloadAllPdfs" class="spinner-border spinner-border-sm"></span>
                    Download All Reports
                </button>
            </div>
        </div>
        <div class="card-body table-responsive">
            @if($jobStatusMessage)
            <div class="alert alert-info">{{ $jobStatusMessage }}</div>
            @endif
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Roll</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>View Result</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $student->roll_number }}</td>
                        <td>{{ $student->user->name ?? 'N/A' }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>

                            <a href="{{ route('admin.result.show', [
    'studentId' => $student->id,
    'examId' => $selectedExam,
    'classId' => $selectedClass,
    'sectionId' => $selectedSection,
    'sessionId' => $selectedSession,
]) }}" class="btn btn-sm btn-info" target="_blank">View Result</a>

                            <button
                                wire:click="downloadStudentPdf({{ $student->id }}, {{ $selectedExam }}, {{ $selectedClass }}, {{ $selectedSection }}, {{ $selectedSession }})"
                                wire:loading.attr="disabled"
                                wire:target="downloadStudentPdf({{ $student->id }}, {{ $selectedExam }}, {{ $selectedClass }}, {{ $selectedSection }}, {{ $selectedSession }})"
                                class="btn btn-sm btn-primary">
                                <span wire:loading.remove
                                    wire:target="downloadStudentPdf({{ $student->id }}, {{ $selectedExam }}, {{ $selectedClass }}, {{ $selectedSection }}, {{ $selectedSession }})">
                                    Download PDF
                                </span>
                                <span wire:loading
                                    wire:target="downloadStudentPdf({{ $student->id }}, {{ $selectedExam }}, {{ $selectedClass }}, {{ $selectedSection }}, {{ $selectedSession }})">
                                    Downloading...
                                </span>
                            </button>



                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>