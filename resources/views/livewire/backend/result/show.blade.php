<div wire:init="loadReport">
    @if(!$readyToLoad)
    <div class="d-flex justify-content-center align-items-center" style="min-height: 300px; flex-direction: column;">
        <div class="spinner-border text-primary mb-2" role="status"></div>
        <div class="w-75">
            <div class="progress" style="height: 15px; border: 1px solid #007bff; border-radius: 5px;">
                <div id="progress-bar"
                    class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                    role="progressbar" style="width: 0%">0%
                </div>
            </div>
        </div>
        <p class="mt-2">Generating Student's Result Sheet...</p>
    </div>
    @else
    <div class="report-box mb-5">
        <div class="school-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="school-info">
                    <div class="d-flex align-items-center gap-2">
                        <div class="logo">
                            <img src="{{ url('assets/frontend/images/kcgs-logo.png') }}" alt="School Logo">
                        </div>
                        <div class="info">
                            <div class="title">Khalishpur Collegiate Girls' School</div>
                            <div class="subtitle">Khalishpur, Khulna</div>
                            <div class="subtitle">Phone: 02477700262, kcgs899@gmail.com</div>
                        </div>
                    </div>
                </div>

                <div class="exam">
                    <h3>{{ $exam->examCategory['name'] }} - {{ $exam->academicSession['name'] }}<br>Progress Report</h3>
                </div>
            </div>

        </div>

        <table class="info-table">
            <tr>
                <td><strong>Name:</strong> {{ $student->user['name'] }}</td>
                <td><strong>Student's ID:</strong> {{ $student->id }}</td>
                <td><strong>Class:</strong> {{ $student->schoolClass['name'] }}</td>
                <td><strong>Section:</strong> {{ $student->classSection['name'] }}</td>
                <td><strong>Roll No:</strong> {{ $student['roll_number'] }}</td>
            </tr>
        </table>

        @php
        $totalObtainedMarks = 0;
        $totalGradePoints = 0;
        $gpaSubjectCount = 0;
        $failAnySubject = false;
        $finalResult = 'Pass';
        $classPosition = 0;
        @endphp

        <div class="section-title">Academic Performance</div>
        <table class="marks-table">
            <thead>
                <tr>
                    <th rowspan="2">Subject</th>
                    <th rowspan="2">Full Mark</th>
                    <th colspan="{{ count($markdistributions) }}">Obtained Marks</th>
                    <th colspan="2">Calculated Marks</th>
                    <th rowspan="2">Total</th>
                    <th rowspan="2">Highest</th>
                    <th rowspan="2">GPA</th>
                    <th rowspan="2">Grade</th>
                    <th rowspan="2">Result</th>
                </tr>
                <tr>
                    {{-- THIS LOGIC IS SLIGHTLY CHANGED --}}
                    @foreach ($markdistributions as $distribution)
                    <th>{{ $distribution['name'] }}</th>
                    @endforeach

                    <th>Class Test</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($marks as $mark)
                @php
                // These queries have been removed as they are slow and no longer needed.
                // $subject = App\Models\Subject::where('id', $mark['subject_id'])->first();
                // $fourthSubject = App\Models\StudentMark::where(...);

                if($mark['fail_any_distribution']){
                $finalResult = 'Fail';
                $failAnySubject = true;
                }
                @endphp

                {{-- This @if check is no longer needed because the component already separated the 4th subject --}}
                {{-- @if($fourthSubject && $fourthSubject->subject_id == $subject->id) @continue @endif --}}

                <tr>
                    <td>{{ $mark['subject_name'] }}</td>
                    <td>{{ $mark['full_mark'] }}</td>
                    @foreach ($mark['obtained_marks'] as $obtainedMark)
                    <td>{!! $obtainedMark !!}</td>
                    @endforeach
                    <td>{{ $mark['class_test_result'] }}</td>
                    <td>{{ $mark['other_parts_total'] }}</td>
                    <td>{{ $mark['total_calculated_marks'] }}</td>
                    <td>{{ $mark['highest_mark'] }}</td>
                    <td>{{ $mark['grade_name'] }}</td>
                    <td>{{ $mark['grade_point'] }}</td>
                    <td>{!! $mark['final_result'] !!}</td>
                </tr>

                @php
                if (!$mark['exclude_from_gpa']) {
                $totalObtainedMarks += $mark['total_calculated_marks'];
                $totalGradePoints += $mark['grade_point'];
                $gpaSubjectCount++;
                }
                @endphp
                @endforeach


                <!-- 4th subject -->
                @if($fourthSubjectMarks)
                <tr style="background-color: #f0f0f0;">
                    <td>{{ $fourthSubjectMarks['subject_name'] }} (4th Subject)</td>
                    <td>{{ $fourthSubjectMarks['full_mark'] }}</td>

                    @foreach ($fourthSubjectMarks['obtained_marks'] as $obtainedMark)
                    <td>{!! $obtainedMark !!}</td>
                    @endforeach
                    <td>{{ $fourthSubjectMarks['class_test_result'] }}</td>
                    <td>{{ $fourthSubjectMarks['other_parts_total'] }}</td>
                    <td>{{ $fourthSubjectMarks['total_calculated_marks'] }}</td>
                    <td>{{ $fourthSubjectMarks['highest_mark'] }}</td>
                    <td>{{ $fourthSubjectMarks['grade_name'] }}</td>
                    <td>{{ $fourthSubjectMarks['grade_point'] }}</td>
                    <td>{!! $fourthSubjectMarks['final_result'] !!}</td>
                </tr>

                @php
                $totalObtainedMarks += $fourthSubjectMarks['total_calculated_marks'];
                if($fourthSubjectMarks['grade_point'] >= 2.0) {
                $totalGradePoints = $totalGradePoints + ($fourthSubjectMarks['grade_point'] - 2.0); // Adjusting for 4th subject
                }
                @endphp
                @endif
            </tbody>
        </table>

        @php
        $finalgpa = $gpaSubjectCount > 0 ? round($totalGradePoints / $gpaSubjectCount, 2) : 0.00;

        $finalGrade = App\Models\Grade::where('grade_point', '<=', $finalgpa)
            ->orderBy('grade_point', 'desc')
            ->first();

            $letterGrade = $finalGrade ? $finalGrade->grade_name : 'N/A';

            // Override if failed any subject
            if ($finalResult === 'Fail') {
            $letterGrade = 'F';
            $finalgpa = 0.00;
            }

            // This helper will now work correctly because the component provides the $students variable
            $studentResult = App\Helpers\ClassPositionHelper::getStudentPosition($student->id, $students, $exam->id);
            $classPosition = $studentResult['position_in_class'] ? $studentResult['position_in_class'] : 0;
            @endphp

            <table class="info-table">
                <tr>
                    <td><strong>Obtained Total:</strong> {{ $totalObtainedMarks }}</td>
                    <td><strong>Letter Grade:</strong> {{ $letterGrade }}</td>
                    <td><strong>GPA:</strong> {{ is_numeric($finalgpa) ? number_format($finalgpa, 2) : $finalgpa }} </td>
                    <td>
                        <strong>Result:</strong>
                        @if($finalResult === 'Fail')
                        <span style="color:red;">Fail</span>
                        @else
                        Pass
                        @endif
                    </td>
                    <td><strong>Position in Class:</strong> {{ $classPosition }}</td>
                </tr>
            </table>

            <div class="section-title">Class Teacher's Comment</div>
            <div class="comment-box"></div>

            <table class="info-table">
                <tr>
                    <td><strong>Period:</strong> {{ date('d-m-Y', strtotime($exam->start_at)) }} - {{ date('d-m-Y', strtotime($exam->end_at)) }}</td>
                    <td><strong>Published Date:</strong> {{ date('d-m-Y') }}</td>
                </tr>
            </table>

            <div class="result-footer">
                <div class="signature">Class Teacher</div>
                <div class="signature">Principal</div>
            </div>
    </div>
    @endif
</div>

{{-- SCRIPT IS UNCHANGED AND CORRECT --}}
<script>
    document.addEventListener("livewire:init", () => {
        let progressBar = document.getElementById("progress-bar");
        if (!progressBar) return;

        let width = 0;
        let interval = setInterval(() => {
            if (width < 95) { // keep it below 100 until load finishes
                width++;
                progressBar.style.width = width + "%";
                progressBar.innerText = width + "%";
            }
        }, 100);

        Livewire.hook("message.processed", (message, component) => {
            if (@this.readyToLoad) {
                clearInterval(interval);
                progressBar.style.width = "100%";
                progressBar.innerText = "100%";
            }
        });
    });
</script>