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
                <th colspan="2">Calculated Marks</th>
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

                <th>Class Test</th>
                <th>Total</th>
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
                    @if($markDistribution['name'] != 'Class Test')
                    @php $failedAnyDistribution = true; @endphp
                    @endif
                    <span style="color:red;">Absent</span>
                    @elseif(!$isPass)
                    <span style="color:red;">Fail ({{ $marksObtained }})</span>
                    @php $failedAnyDistribution = true; @endphp
                    @else
                    {{ $marksObtained }}
                    @endif
                    @else
                    -
                    @endif
                </td>
                @endforeach

                {{-- Calculated Marks --}}

                <!-- class test -->
                @php

                $ctMarkDistribution = App\Models\MarkDistribution::where('name', 'Class Test')->first();

                $ctSubjectMarkDistribution = App\Models\SubjectMarkDistribution::where('subject_id', $subject->subject['id'])
                ->where('school_class_id', $student->school_class_id)
                ->where('class_section_id', $student->class_section_id)
                ->where('mark_distribution_id', $ctMarkDistribution->id)
                ->first();

                if($ctSubjectMarkDistribution) {
                $classTestMark = App\Models\StudentMark::where('student_id', $student->id)
                ->where('subject_id', $subject->subject['id'])
                ->where('school_class_id', $student->school_class_id)
                ->where('exam_id', $exam->id)
                ->where('mark_distribution_id', $ctMarkDistribution->id)
                ->first();
                } else {
                $classTestMark = null;
                }

                $finalClassTestMark = $classTestMark ? $classTestMark->marks_obtained : 0;
                @endphp

                <td>
                    @if($classTestMark)
                    @if($classTestMark->is_absent)
                    0
                    @else
                    {{ $finalClassTestMark }}
                    @endif
                    @else
                    -
                    @endif
                </td>

                @php

                $otherMarkDistributions = App\Models\MarkDistribution::where('name', '!=', 'Class Test')->get();

                $totalSubjectMark = 0;
                foreach ($otherMarkDistributions as $distribution) {
                $getMarkDistribution = App\Models\MarkDistribution::where('name', $distribution->name)->first();

                $subjectMarkDistribution = App\Models\SubjectMarkDistribution::where('subject_id', $subject->subject['id'])
                ->where('school_class_id', $student->school_class_id)
                ->where('class_section_id', $student->class_section_id)
                ->where('mark_distribution_id', $getMarkDistribution ? $getMarkDistribution->id : null)
                ->first();

                $studentSubjectMark = null;

                if($subjectMarkDistribution) {
                $studentSubjectMark = App\Models\StudentMark::where('student_id', $student->id)
                ->where('subject_id', $subject->subject['id'])
                ->where('school_class_id', $student->school_class_id)
                ->where('exam_id', $exam->id)
                ->where('mark_distribution_id', $getMarkDistribution->id)
                ->first();
                }

                $marksObtained = $studentSubjectMark ? $studentSubjectMark->marks_obtained : 0;
                $totalSubjectMark += $marksObtained;
                }

                $calculatedMark = round(($totalSubjectMark * $finalMarkConfiguration->final_result_weight_percentage) / 100);

                $totalCalculatedMark = $calculatedMark + $finalClassTestMark;
                @endphp

                <td>{{ $calculatedMark }}</td>

                {{-- Total Calculated Mark --}}
                <td>{{ $totalCalculatedMark }}</td>

                {{-- Highest Mark --}}
                @php
                $highestMark = App\Helpers\SchoolHighestMarkHelper::getHighestMark($students, $subject->subject['id'], $student->school_class_id, $student->class_section_id, $exam->id);
                @endphp
                <td>{{ $highestMark['highest_mark'] }}</td>

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


            if (!$excludeFromGPA) {
            $totalObtainedMarks += $totalCalculatedMark;
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

        $studentResult = App\Helpers\ClassPositionHelper::getStudentPosition($student->id, $students, $exam->id);
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