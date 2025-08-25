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
            padding: 5px;
        }

        .school-header {
            text-align: center;

        }

        .boarder-top {
            border-top: 2px solid #000;
            margin-top: 5px;
            padding-top: 5px;
        }

        .school-header img {
            height: 75px;
        }

        .title {
            font-size: 20px;
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
            margin-top: 10px;
            text-decoration: underline;
        }

        .result-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .signature {
            text-align: center;
            width: 200px;
            border-top: 1px solid #000;
        }

        .comment-box {
            border: 1px solid #000;
            height: 20px;
            padding: 5px;
        }
    </style>
</head>

<body>

    <div class="report-box">
        <div class="school-header" style="width: 100%; height: 75px;">
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

                @foreach ($marks as $mark)
                @php
                $totalCalculatedMark = 0;
                $excludeFromGPA = false;

                $subject = App\Models\Subject::where('id', $mark['subject_id'])->first();

                $fourthSubject = App\Models\StudentMark::where('student_id', $student->id)
                ->where('exam_id', $exam->id)
                ->where('is_fourth_subject', 1)
                ->first();

                if($mark['exclude_from_gpa']) {
                $excludeFromGPA = true;
                }

                if($mark['fail_any_distribution']){
                $finalResult = 'Fail';
                $failAnySubject = true;
                }
                @endphp

                @if($fourthSubject && $fourthSubject->subject_id == $subject->id)
                @continue
                @endif

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

                    <td> {{ $mark['grade_point'] }}</td>
                    <td>{!! $mark['final_result'] !!}</td>
                </tr>


                @php


                if (!$excludeFromGPA) {
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

            $studentResult = App\Helpers\ClassPositionHelper::getStudentPosition($student->id, $students, $exam->id);
            $classPosition = $studentResult['position'] ? $studentResult['position'] : 0;

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

            <div class="section-title" style="margin-bottom: 5px;">Class Teacher's Comment</div>
            <div class="comment-box"></div>

            <table class="info-table">
                <tr>
                    <td><strong>Period:</strong> {{ date('d-m-Y', strtotime($exam->start_at)) }} - {{ date('d-m-Y', strtotime($exam->end_at)) }}</td>
                    <td><strong>Published Date:</strong> {{ date('d-m-Y') }}</td>
                </tr>
            </table>

            <div style="width: 100%; margin-top: 25px; margin-bottom: 15px;">
                <div style="float: left; border-top: 1px solid #000; padding: 3px 20px 0 0;">
                    Class Teacher
                </div>
                <div style="float: right; border-top: 1px solid #000; padding: 3px 0 0 20px;">
                    Principal
                </div>
            </div>
    </div>

</body>

</html>