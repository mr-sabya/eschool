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
    <div class="card">
        <div class="card-header">Students List ({{ $students->count() }})</div>
        <div class="card-body table-responsive">
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>