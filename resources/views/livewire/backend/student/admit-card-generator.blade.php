<div class="card">
    <div class="card-header bg-primary">
        <h5 class="card-title m-0 text-white">Generate Admit Cards</h5>
    </div>
    <div class="card-body">
        <!-- Filter Row -->
        <div class="row g-3 mb-3 border-bottom pb-3">
            <div class="col-md-3">
                <label>Academic Session</label>
                <select wire:model.live="selectedSessionId" class="form-select">
                    <option value="">Select Session</option>
                    @foreach($academicSessions as $session) <option value="{{ $session->id }}">{{ $session->name }}</option> @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Exam</label>
                <select wire:model.live="selectedExamId" class="form-select" {{ !$selectedSessionId ? 'disabled' : '' }}>
                    <option value="">Select Exam</option>
                    @foreach($exams as $exam)
                    <option value="{{ $exam->id }}">
                        {{ $exam->examCategory->name ?? 'Exam' }} ({{ \Carbon\Carbon::parse($exam->start_at)->format('d M, Y') }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Class</label>
                <select wire:model.live="selectedClassId" class="form-select" {{ !$selectedExamId ? 'disabled' : '' }}>
                    <option value="">Select Class</option>
                    @foreach($schoolClasses as $class) <option value="{{ $class->id }}">{{ $class->name }}</option> @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Section</label>
                <select wire:model.live="selectedSectionId" class="form-select" {{ !$selectedClassId ? 'disabled' : '' }}>
                    <option value="">All Sections</option>
                    @foreach($classSections as $section) <option value="{{ $section->id }}">{{ $section->name }}</option> @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Department</label>
                <select wire:model.live="selectedDepartmentId" class="form-select" {{ !$selectedClassId ? 'disabled' : '' }}>
                    <option value="">All Departments</option>
                    @foreach($departments as $dept) <option value="{{ $dept->id }}">{{ $dept->name }}</option> @endforeach
                </select>
            </div>
        </div>

        <!-- Student List -->
        @if($students->isNotEmpty())
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" wire:model.live="selectAll" id="selectAllCheckbox">
                <label class="form-check-label" for="selectAllCheckbox">
                    Select All ({{ count($selectedStudents) }} / {{ $students->count() }} selected)
                </label>
            </div>
            <button type="button"
                wire:click="generateAdmitCards"
                class="btn btn-success"
                @if(!$selectedExamId || empty($selectedStudents)) disabled @endif>

                {{-- This adds a nice loading spinner for better UX --}}
                <span wire:loading wire:target="generateAdmitCards" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>

                <i class="ri-download-2-line" wire:loading.remove wire:target="generateAdmitCards"></i>
                Generate Selected Admit Cards
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>Roll</th>
                        <th>Name</th>
                        <th>Class & Section</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr wire:key="{{ $student->id }}">
                        <td><input type="checkbox" wire:model.live="selectedStudents" value="{{ $student->id }}"></td>
                        <td>{{ $student->roll_number }}</td>
                        <td>{{ $student->user->name }}</td>
                        <td>{{ $student->schoolClass->name }} ({{ $student->classSection->name }})</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif($selectedClassId)
        <div class="alert alert-info">No students found matching your criteria.</div>
        @endif
    </div>
</div>