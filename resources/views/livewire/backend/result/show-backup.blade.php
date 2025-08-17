<div class="report-box">
    <div class="school-header">
        <img src="{{ url('assets/frontend/images/kcgs-logo.png') }}" alt="School Logo">
        <div class="title">Khalishpur Collegiate Girls' School</div>
        <div class="subtitle">Khalishpur, Khulna</div>
        <div class="subtitle">Phone: 02477700262, kcgs899@gmail.com</div>
        <h3>{{ $exam->examCategory['name'] }} - {{ $exam->academicSession['name'] }}<br>Progress Report</h3>
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
    $classPosition = 0;
    @endphp

    <div class="section-title">Academic Performance</div>
    <table class="marks-table">
        <thead>
            <tr>
                <th rowspan="2">Subject</th>
                <th rowspan="2">Full Mark</th>
                <th colspan="{{ count($markdistributions) }}">Obtained Marks</th>
                <th colspan="{{ count($markdistributions) }}">Calculated Marks</th>
                <th rowspan="2">Total</th>
                <th rowspan="2">Highest</th>
                <th rowspan="2">GPA</th>
                <th rowspan="2">Grade</th>
                <th rowspan="2">Result</th>
            </tr>
            <tr>
                @foreach ($markdistributions as $distribution)
                <th>{{ $distribution->markDistribution['name'] }}</th>
                @endforeach
                @foreach ($markdistributions as $distribution)
                <th>{{ $distribution->markDistribution['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject)
            @php
            $finalMarkConfiguration = App\Models\FinalMarkConfiguration::where('school_class_id', $student->school_class_id)
            ->where('subject_id', $subject->subject['id'])
            ->first();

            $annualFullMark = $finalMarkConfiguration ? $finalMarkConfiguration->other_parts_total : 0;

            // Check if any mark distribution for this subject excludes it from GPA
            $excludeFromGPA = $finalMarkConfiguration ? $finalMarkConfiguration->exclude_from_gpa : false;

            $totalCalculatedMark = 0;
            $failedAnyDistribution = false;
            @endphp

            <tr>
                <td>{{ $subject->subject['name'] }}</td>
                <td>{{ $annualFullMark }}</td>

                {{-- Obtained Marks --}}
                @foreach ($markdistributions as $distribution)
                @php
                $markDistribution = App\Models\MarkDistribution::where('name', $distribution->markDistribution['name'])->first();

                $subjectMarkDistribution = App\Models\SubjectMarkDistribution::where('subject_id', $subject->subject['id'])
                ->where('school_class_id', $student->school_class_id)
                ->where('class_section_id', $student->class_section_id)
                ->where('mark_distribution_id', $markDistribution ? $markDistribution->id : null)
                ->first();

                $studentSubjectMark = null;
                if ($subjectMarkDistribution) {
                $studentSubjectMark = App\Models\StudentMark::where('student_id', $student->id)
                ->where('subject_id', $subject->subject['id'])
                ->where('school_class_id', $student->school_class_id)
                ->where('exam_id', $exam->id)
                ->where('mark_distribution_id', $markDistribution->id)
                ->first();
                }
                @endphp
                <td>
                    @if($studentSubjectMark)
                    @php
                    $passMark = $subjectMarkDistribution->pass_mark ?? 0;
                    $marksObtained = $studentSubjectMark->marks_obtained;
                    $isPass = $marksObtained >= $passMark;
                    @endphp

                    @if($studentSubjectMark->is_absent)
                    Absent
                    @elseif(!$isPass)
                    <span style="color:red;">Fail ({{ $marksObtained }})</span>
                    @php $failedAnyDistribution = true; @endphp
                    @else
                    {{ $marksObtained }}
                    @endif
                    @else
                    N/A
                    @endif
                </td>
                @endforeach

                {{-- Calculated Marks --}}

                @foreach ($markdistributions as $distribution)
                @php
                $markDistribution = App\Models\MarkDistribution::where('name', $distribution->markDistribution['name'])->first();

                $subjectMarkDistribution = App\Models\SubjectMarkDistribution::where('subject_id', $subject->subject['id'])
                ->where('school_class_id', $student->school_class_id)
                ->where('class_section_id', $student->class_section_id)
                ->where('mark_distribution_id', $markDistribution ? $markDistribution->id : null)
                ->first();

                $studentSubjectMark = null;
                $studentClassTestMark = null;
                if ($subjectMarkDistribution) {
                if($distribution->markDistribution['name'] == 'Class Test'){
                $studentClassTestMark = App\Models\StudentMark::where('student_id', $student->id)
                ->where('subject_id', $subject->subject['id'])
                ->where('school_class_id', $student->school_class_id)
                ->where('exam_id', $exam->id)
                ->where('mark_distribution_id', $markDistribution->id)
                ->first();
                }else{
                $studentSubjectMark = App\Models\StudentMark::where('student_id', $student->id)
                ->where('subject_id', $subject->subject['id'])
                ->where('school_class_id', $student->school_class_id)
                ->where('exam_id', $exam->id)
                ->where('mark_distribution_id', $markDistribution->id)
                ->first();
                }

                }

                $calculatedMark = 0;
                if ($studentSubjectMark) {
                $calculatedMark = round(($studentSubjectMark->marks_obtained * $finalMarkConfiguration->final_result_weight_percentage) / 100);
                }

                if($studentClassTestMark){

                $draftMark = $studentClassTestMark->marks_obtained + $calculatedMark;
                }else{
                $draftMark = $calculatedMark;
                }

                $totalCalculatedMark += $draftMark;
                @endphp

                <td>
                    @if($distribution->markDistribution['name'] == 'Class Test')
                    @if($studentClassTestMark)
                    {{ $studentClassTestMark->marks_obtained }}
                    @else
                    N/A
                    @endif
                    @else

                    @if($studentSubjectMark)
                    {{ $studentSubjectMark->is_absent ? 'Absent' : $calculatedMark }}
                    @else
                    N/A
                    @endif
                    @endif
                </td>
                @endforeach

                {{-- Total Calculated Mark --}}
                <td>{{ $totalCalculatedMark }}</td>

                {{-- Highest Mark --}}
                @php
                $highestMark = App\Models\StudentMark::where('subject_id', $subject->subject['id'])
                ->where('school_class_id', $student->school_class_id)
                ->where('exam_id', $exam->id)
                ->max('marks_obtained');
                @endphp
                <td>{{ $highestMark }}</td>

                {{-- GPA and Grade --}}
                @php
                $grade = App\Models\Grade::where('start_marks', '<=', $totalCalculatedMark)
                    ->where('end_marks', '>=', $totalCalculatedMark)
                    ->where('grading_scale', $finalMarkConfiguration->grading_scale)
                    ->first();

                    $gradeName = $grade ? $grade->grade_name : 'N/A';
                    $gradePoint = $grade ? $grade->grade_point : 0;
                    @endphp

                    <td>{{ $gradeName }}</td>
                    <td>{{ $gradePoint }}</td>

                    {{-- Result: Fail if any distribution failed --}}
                    <td>

                        @if($failedAnyDistribution)
                        <span style="color:red;">Fail</span>
                        @php $finalResult = 'Fail'; @endphp
                        @else
                        Pass
                        @endif
                    </td>
            </tr>

            @php
            $totalObtainedMarks += $totalCalculatedMark;

            if (!$excludeFromGPA) {
            $totalGradePoints += $gradePoint;
            $gpaSubjectCount++;
            }
            @endphp
            @endforeach
        </tbody>
    </table>

    @php
    $finalgpa = $gpaSubjectCount > 0 ? round($totalGradePoints / $gpaSubjectCount, 2) : 0.00;

    $finalGrade = App\Models\Grade::where('grading_scale', $finalMarkConfiguration->grading_scale)
    ->where('grade_point', '<=', $finalgpa)
        ->orderBy('grade_point', 'desc')
        ->first();

        $letterGrade = $finalGrade ? $finalGrade->grade_name : 'N/A';

        // Override if failed any subject
        if ($finalResult === 'Fail') {
        $letterGrade = 'F';
        $finalgpa = 0.00;
        }

        $studentResult = App\Helpers\ResultHelper::getStudentPosition($student->id, $students, $exam->id);
        $classPosition = $studentResult['position'] ? $studentResult['position'] : 0;

        @endphp

        <table class="info-table">
            <tr>
                <td><strong>Obtained Total:</strong> {{ $totalObtainedMarks }}</td>
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