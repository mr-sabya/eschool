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
        $finalResult = 'Pass';

        // Calculate the colspan for the "Calculated Marks" header dynamically.
        $calculatedMarksColspan = ($hasClassTest ? 1 : 0) + ($hasOtherMarks ? 1 : 0);
        @endphp

        <div class="section-title">Academic Performance</div>
        <table class="marks-table">
            <thead>
                <tr>
                    <th rowspan="2">Subject</th>
                    <th rowspan="2">Full Mark</th>
                    <th colspan="{{ count($markdistributions) }}">Obtained Marks</th>

                    {{-- Only show the "Calculated Marks" header if there is at least one column to show under it. --}}
                    @if ($calculatedMarksColspan > 0)
                    <th colspan="{{ $calculatedMarksColspan }}">Calculated Marks</th>
                    @endif

                    <th rowspan="2">Total</th>
                    <th rowspan="2">Highest</th>
                    <th rowspan="2">GPA</th>
                    <th rowspan="2">Grade</th>
                    <th rowspan="2">Result</th>
                </tr>
                <tr>
                    {{-- This loop generates the headers for each visible mark distribution. --}}
                    @foreach ($markdistributions as $distribution)
                    <th>{{ $distribution['name'] }}</th>
                    @endforeach

                    {{-- Conditionally show the sub-headers for calculated marks based on the flags from the component. --}}
                    @if ($hasClassTest)
                    <th>Class Test</th>
                    @endif
                    @if ($hasOtherMarks)
                    <th>Total</th> {{-- This represents the weighted total of other marks --}}
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($marks as $mark)
                @php
                if($mark['fail_any_distribution']){
                $finalResult = 'Fail';
                }
                @endphp
                <tr>
                    <td>{{ $mark['subject_name'] }}</td>
                    <td>{{ $mark['full_mark'] }}</td>
                    @foreach ($mark['obtained_marks'] as $obtainedMark)
                    <td>{!! $obtainedMark !!}</td>
                    @endforeach

                    {{-- Conditionally show the data cells to match the headers. --}}
                    @if ($hasClassTest)
                    <td>{{ $mark['class_test_result'] }}</td>
                    @endif
                    @if ($hasOtherMarks)
                    <td>{{ $mark['other_parts_total'] }}</td>
                    @endif

                    <td>{{ $mark['total_calculated_marks'] }}</td>
                    <td>{{ $mark['highest_mark'] }}</td>
                    <td>{{ $mark['grade_point'] }}</td>
                    <td>{{ $mark['grade_name'] }}</td>
                    <td>{!! $mark['final_result'] !!}</td>
                </tr>
                @php
                if (!$mark['exclude_from_gpa']) {
                $totalObtainedMarks += $mark['total_calculated_marks'];
                $totalGradePoints += $mark['grade_point'];
                $gpaSubjectCount++;
                }

                @endphp
                @empty
                <tr>
                    <td colspan="100%" class="text-center text-danger">No marks found for this student.</td>
                </tr>
                @endforelse


                <!-- 4th subject -->
                @if($fourthSubjectMarks)
                @php
                if($fourthSubjectMarks['fail_any_distribution']){
                $finalResult = 'Fail';
                }
                @endphp
                <tr style="background-color: #f0f0f0;">
                    <td>{{ $fourthSubjectMarks['subject_name'] }} (4th Subject) </td>
                    <td>{{ $fourthSubjectMarks['full_mark'] }}</td>
                    @foreach ($fourthSubjectMarks['obtained_marks'] as $obtainedMark)
                    <td>{!! $obtainedMark !!}</td>
                    @endforeach

                    {{-- Also apply the conditional logic to the 4th subject row. --}}
                    @if ($hasClassTest)
                    <td>{{ $fourthSubjectMarks['class_test_result'] }}</td>
                    @endif
                    @if ($hasOtherMarks)
                    <td>{{ $fourthSubjectMarks['other_parts_total'] }}</td>
                    @endif

                    <td>{{ $fourthSubjectMarks['total_calculated_marks'] }}</td>
                    <td>{{ $fourthSubjectMarks['highest_mark'] }}</td>
                    <td>{{ $fourthSubjectMarks['grade_point'] }}</td>
                    <td>{{ $fourthSubjectMarks['grade_name'] }}</td>
                    <td>{!! $fourthSubjectMarks['final_result'] !!}</td>
                </tr>
                @php
                $checkIfGPA5 = $totalGradePoints / $gpaSubjectCount;
                $totalObtainedMarks +=$fourthSubjectMarks['total_calculated_marks'];
                if($checkIfGPA5 < 5.0){
                    if($fourthSubjectMarks['grade_point']>= 2.0 && !$fourthSubjectMarks['fail_any_distribution'])
                    {
                    $totalGradePoints += ($fourthSubjectMarks['grade_point'] - 2.0);
                    }
                    }
                    @endphp
                    @endif
            </tbody>
        </table>

        @php
        $finalgpa = $gpaSubjectCount > 0 ? round($totalGradePoints / $gpaSubjectCount, 2) : 0.00;
        // Force minimum GPA = 5 if total grade points < 5
            if ($totalGradePoints > 5 && $finalgpa > 5) {
                $finalgpa = 5.00;
            }
            $finalGrade = App\Models\Grade::where('start_marks', '<=', ($finalgpa * 20)) // Assuming a 100-mark scale where GPA 5=100
            ->where('end_marks', '>=', ($finalgpa * 20))
            ->where('grading_scale', 100)
            ->first();
            $letterGrade = $finalGrade ? $finalGrade->grade_name : 'N/A';

            // Override if failed any subject
            if ($finalResult === 'Fail') {
            $letterGrade = 'F';
            $finalgpa = 0.00;
            }

            // This helper will now work correctly because the component provides the $students variable
            $studentResult = App\Helpers\ClassPositionHelper::getStudentPosition($student->id, $students, $exam->id);
            $classPosition = $studentResult['position'] ? $studentResult['position'] : 0;
            @endphp

            <table class="info-table">
                <tr>
                    <td><strong>Obtained Total:</strong> {{ round($totalObtainedMarks) }}</td>
                    <td><strong>Letter Grade:</strong> {{ $letterGrade }}</td>
                    <td><strong>GPA:</strong> {{ is_numeric($finalgpa) ? number_format($finalgpa, 2) : $finalgpa }}</td>
                    <td>
                        <strong>Result:</strong>
                        @if($finalResult === 'Fail')
                        <span style="color:red;">Fail</span>
                        @else
                        Pass
                        @endif
                    </td>
                    {{-- This now safely uses the property from the component, preventing errors and improving performance. --}}
                    <td><strong>Position in Class:</strong> {{ $classPosition }}</td>
                </tr>
            </table>

            <div class="section-title">Class Teacher's Comment</div>
            <div class="comment-box"></div>

            <table class="info-table">
                <tr>
                    <td><strong>Period:</strong> {{ \Carbon\Carbon::parse($exam->start_at)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($exam->end_at)->format('d M, Y') }}</td>
                    <td><strong>Published Date:</strong> {{ \Carbon\Carbon::now()->format('d M, Y') }}</td>
                </tr>
            </table>

            <div class="result-footer">
                <div class="signature">Class Teacher</div>
                <div class="signature">Principal</div>
            </div>
    </div>
    @endif
</div>

{{-- This script for the loading bar is correct and does not need changes. --}}
<script>
    document.addEventListener("livewire:init", () => {
        let progressBar = document.getElementById("progress-bar");
        if (!progressBar) return;

        let width = 0;
        let interval = setInterval(() => {
            if (width < 95) {
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