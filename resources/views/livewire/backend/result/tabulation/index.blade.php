<div>
    <div class="row mb-3">
        <div class="col-md-2">
            <label>Session</label>
            <select wire:model="selectedSession" wire:change="loadExamsForSession" class="form-control">
                <option value="">Select</option>
                @foreach($academicSessions as $session)
                <option value="{{ $session->id }}">{{ $session->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Class</label>
            <select wire:model="selectedClass" wire:change="loadSectionsForClass" class="form-control">
                <option value="">Select</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Section</label>
            <select wire:model="selectedSection" class="form-control">
                <option value="">Select</option>
                @foreach($sections as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Department</label>
            <select wire:model="selectedDepartment" class="form-control">
                <option value="">All</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Exam</label>
            <select wire:model="selectedExam" class="form-control">
                <option value="">Select</option>
                @foreach($exams as $exam)
                <option value="{{ $exam->id }}">{{ $exam->examCategory->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button
                wire:click="generateTabulation"
                class="btn btn-primary w-100"
                wire:loading.attr="disabled"
                wire:target="generateTabulation">
                <!-- Default text -->
                <span wire:loading.remove wire:target="generateTabulation">Generate</span>

                <!-- Loading text -->
                <span wire:loading wire:target="generateTabulation">Generating...</span>
            </button>
        </div>
    </div>

    @if($students->count())
    <div class="d-flex justify-content-between mb-2">
        <h5>Tabulation Sheet - {{ $exam->examCategory->name }} ({{ $exam->academicSession->name }})</h5>
        <button wire:click="downloadTabulationPdf" class="btn btn-danger">Download PDF</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm text-center">
            <thead>
                <tr>
                    <th rowspan="2">SL</th>
                    <th rowspan="2">Student ID</th>
                    <th rowspan="2">Name</th>
                    <th rowspan="2">Roll</th>

                    @foreach($subjects as $subject)
                    <th colspan="5">{{ $subject->subject->name }}</th>
                    @endforeach

                    <th rowspan="2">Total (All Subjects)</th>
                    <th rowspan="2">GPA</th>
                    <th rowspan="2">Grade Point</th>
                </tr>

                <tr>
                    @foreach($subjects as $subject)
                    <th>CT/CA</th>
                    <th>CQ</th>
                    <th>MCQ</th>
                    <th>Total</th>
                    <th>C.T + H.T (%)</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->user->name }}</td>
                    <td>{{ $student->roll_number }}</td>

                    @foreach($subjects as $subject)
                    @php
                    $marks = $tabulationData[$student->id][$subject->subject_id];
                    $ct = is_numeric($marks['class_test_result']) ? (float)$marks['class_test_result'] : 0;
                    $total = $marks['total_calculated_marks'] ?? 0;
                    @endphp
                    <td>{{ $ct }}</td>
                    <td>{{ $marks['obtained_marks'][0] ?? 0 }}</td>
                    <td>{{ $marks['obtained_marks'][1] ?? 0 }}</td>
                    <td>{{ $total }}</td>
                    <td>{{ round(($ct + $marks['other_parts_total']), 2) }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif


</div>