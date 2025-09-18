<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Progress Report - {{ $student->user['name'] }}</title>
    <style>
        /* Using your new styles */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            /* DejaVu Sans is better for PDF special characters */
            font-size: 12px;
        }

        .report-box {
            width: 100%;
            margin: auto;
            border: 1px solid #000;
            padding: 10px;
        }

        .school-header {
            width: 100%;
            height: 80px;
            /* Adjusted height */
            margin-bottom: 10px;
        }

        .header-info-table {
            width: 100%;
            border: none;
        }

        .header-info-table td {
            vertical-align: top;
        }

        .logo {
            width: 80px;
        }

        .logo img {
            width: 100%;
        }

        .school-details {
            padding-left: 15px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 14px;
        }

        .exam-details {
            text-align: right;
        }

        .exam-details h3 {
            font-size: 16px;
            margin: 0;
        }

        .boarder-top {
            border-top: 2px solid #000;
            margin-top: 5px;
            padding-top: 5px;
        }

        .info-table,
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 4px;
        }

        .marks-table th,
        .marks-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        .marks-table th {
            font-size: 11px;
            background-color: #f2f2f2;
        }

        .marks-table td:first-child {
            text-align: left;
            /* Align subject names to the left */
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            text-decoration: underline;
        }

        .comment-box {
            border: 1px solid #000;
            height: 20px;
            padding: 5px;
        }

        .footer-signatures {
            width: 100%;
            margin-top: 20px;
            /* More space for signatures */
            margin-bottom: 10px;
        }

        .footer-signatures .teacher {
            float: left;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .footer-signatures .principal {
            float: right;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        span[style*="color:red;"] {
            color: red !important;
        }
    </style>
</head>

<body>

    <div class="report-box">
        <div class="school-header">
            <table class="header-info-table">
                <tr>
                    <td style="width: 80px;">
                        <div class="logo">
                            <img src="{{ public_path('assets/frontend/images/kcgs-logo.png') }}" alt="School Logo">
                        </div>
                    </td>
                    <td class="school-details">
                        <div class="title">Khalishpur Collegiate Girls' School</div>
                        <div class="subtitle">Khalishpur, Khulna</div>
                        <div class="subtitle">Phone: 02477700262, kcgs899@gmail.com</div>
                    </td>
                    <td class="exam-details">
                        <h3>{{ $exam->examCategory['name'] }} - {{ $exam->academicSession['name'] }}<br>Progress Report</h3>
                    </td>
                </tr>
            </table>
        </div>

        <div class="boarder-top">
            <table class="info-table">
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
        $finalResult = 'Pass'; // Assume Pass initially
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
                    <th rowspan="2">Grade</th>
                    <th rowspan="2">GPA</th>
                    <th rowspan="2">Result</th>
                </tr>
                <tr>
                    {{-- Corrected loop for optimized component data --}}
                    @foreach ($markdistributions as $distribution)
                    <th>{{ $distribution->name }}</th>
                    @endforeach
                    <th>Class Test</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                {{-- OPTIMIZED LOOP: No database queries inside the view --}}
                @foreach ($marks as $mark)
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
                $totalGradePoints += ($fourthSubjectMarks['grade_point'] - 2.0);
                }
                if ($fourthSubjectMarks['fail_any_distribution']) {
                $finalResult = 'Fail';
                }
                @endphp
                @endif
            </tbody>
        </table>

        @php
        $finalgpa = $gpaSubjectCount > 0 ? round($totalGradePoints / $gpaSubjectCount, 2) : 0.00;
        $finalGrade = \App\Models\Grade::where('grade_point', '<=', $finalgpa)->orderBy('grade_point', 'desc')->first();
            $letterGrade = $finalGrade ? $finalGrade->grade_name : 'N/A';

            // Override if failed any subject
            if ($finalResult === 'Fail') {
            $letterGrade = 'F';
            $finalgpa = 0.00;
            }

            $studentResult = \App\Helpers\ClassPositionHelper::getStudentPosition($student->id, $students, $exam->id);
            $classPosition = $studentResult['position'] ?? 'N/A';
            @endphp

            <table class="info-table">
                <tr>
                    <td><strong>Obtained Total:</strong> {{ $totalObtainedMarks }}</td>
                    <td><strong>Letter Grade:</strong> {{ $letterGrade }}</td>
                    <td><strong>GPA:</strong> {{ number_format($finalgpa, 2) }} </td>
                    <td>
                        <strong>Result:</strong>
                        {!! $finalResult === 'Fail' ? '<span style="color:red;">Fail</span>' : 'Pass' !!}
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

            <div class="footer-signatures">
                <div class="teacher">Class Teacher</div>
                <div class="principal">Principal</div>
            </div>
    </div>

</body>

</html>