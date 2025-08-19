<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Class Result PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        /* report */
        .report-box {
            max-width: 1000px;
            margin: auto;
            border: 1px solid #000;
            padding: 10px;
        }

        .school-header {
            text-align: center;

        }

        .boarder-top {
            border-top: 2px solid #000;
            margin-top: 10px;
            margin-bottom: 10px;
            padding-top: 10px;
        }

        .school-header img {
            height: 80px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 14px;
        }

        .info-table,
        .grade-table,
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 4px;
        }

        .marks-table th,
        .marks-table td,
        .grade-table th,
        .grade-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        .marks-table th {
            font-size: 11px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 15px;
            text-decoration: underline;
        }

        .result-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature {
            text-align: center;
            width: 200px;
            border-top: 1px solid #000;
        }

        .comment-box {
            border: 1px solid #000;
            height: 30px;
            padding: 5px;
        }
    </style>
</head>

<body>

    <div class="report-box">
        <div class="school-header" style="width: 100%; height: 85px;">
            <table style="text-align: left; float: left;">
                <tr>
                    <td>
                        <img src="{{ public_path('assets/frontend/images/kcgs-logo.png') }}" alt="School Logo">
                    </td>
                    <td>
                        <div class="title">Khalishpur Collegiate Girls' School</div>
                        <div class="subtitle">Khalishpur, Khulna</div>
                        <div class="subtitle">Phone: 02477700262, kcgs899@gmail.com</div>
                    </td>

                </tr>
            </table>
            <div style="float: right; padding-right: 20px; text-align: right;">
                <h3>{{ $exam->examCategory['name'] }} - {{ $exam->academicSession['name'] }}<br>Progress Report</h3>
            </div>
        </div>
        <div class="boarder-top">
            <table class="info-table ">
                <tr>
                    <td><strong>Name:</strong> {{ $student->user['name'] }}</td>
                    <td><strong>Student's ID:</strong> {{ $student->id }}</td>
                    <td><strong>Class:</strong> {{ $student->schoolClass['name'] }}</td>
                    <td><strong>Section:</strong> {{ $student->classSection['name'] }}</td>
                    <td><strong>Roll No:</strong> {{ $student['roll_number'] }}</td>
                </tr>
            </table>
        </div>

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

                    $markDistribution = App\Models\MarkDistribution::where('name', 'Class Test')->first();

                    $classTestMark = App\Models\StudentMark::where('student_id', $student->id)
                    ->where('subject_id', $subject->subject['id'])
                    ->where('school_class_id', $student->school_class_id)
                    ->where('exam_id', $exam->id)
                    ->where('mark_distribution_id', $markDistribution->id)
                    ->first();

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
                    $markDistribution = App\Models\MarkDistribution::where('name', $distribution->name)->first();

                    $studentSubjectMark = App\Models\StudentMark::where('student_id', $student->id)
                    ->where('subject_id', $subject->subject['id'])
                    ->where('school_class_id', $student->school_class_id)
                    ->where('exam_id', $exam->id)
                    ->where('mark_distribution_id', $markDistribution->id)
                    ->first();
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

            <div class="section-title" style="margin-bottom: 5px;">Class Teacher's Comment</div>
            <div class="comment-box"></div>

            <table class="info-table">
                <tr>
                    <td><strong>Period:</strong> {{ date('d-m-Y', strtotime($exam->start_at)) }} - {{ date('d-m-Y', strtotime($exam->end_at)) }}</td>
                    <td><strong>Published Date:</strong> {{ date('d-m-Y') }}</td>
                </tr>
            </table>

            <div style="width: 100%; margin-top: 50px; margin-bottom: 25px;">
                <div style="float: left; border-top: 1px solid #000; padding: 5px 20px 0 0;">
                    Class Teacher
                </div>
                <div style="float: right; border-top: 1px solid #000; padding: 5px 0 0 20px;">
                    Principal
                </div>
            </div>
    </div>

</body>

</html>