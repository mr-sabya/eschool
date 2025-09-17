<div wire:init="loadData">
    @if(!$exam)
    <div class="text-center py-5">
        <div class="spinner-border text-primary"></div>
        <p>Loading Tabulation Sheet...</p>
    </div>
    @else
    <div class="d-flex justify-content-between mb-3">
        <h4>Tabulation Sheet - {{ $exam->examCategory->name }} ({{ $exam->academicSession->name }})</h4>
        <button wire:click="downloadPdf" class="btn btn-danger">
            Download PDF
        </button>
    </div>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>SL</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Roll</th>
                @foreach($subjects as $sub)
                <th>{{ $sub->subject->name }}</th>
                @endforeach
                <th>Total</th>
                <th>GPA</th>
                <th>Grade</th>
                <th>Position</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            @php
            $studentResult = ClassPositionHelper::getStudentPosition(
            $student->id, $students, $exam->id
            );
            @endphp
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $student->id }}</td>
                <td style="text-align: left">{{ $student->user->name }}</td>
                <td>{{ $student->roll_number }}</td>

                @foreach($subjects as $sub)
                @php
                $marks = SchoolHighestMarkHelper::getStudentSubjectMarks(
                $student->id, $sub->subject_id, $classId, $sectionId, $exam->id
                );
                @endphp
                <td>{{ $marks['total'] ?? '-' }}</td>
                @endforeach

                <td>{{ $studentResult['total'] }}</td>
                <td>{{ number_format($studentResult['gpa'], 2) }}</td>
                <td>{{ $studentResult['grade'] }}</td>
                <td>{{ $studentResult['position'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>