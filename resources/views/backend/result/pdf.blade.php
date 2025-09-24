<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Progress Report - {{ $student->user['name'] }}</title>
    <style>
        /* This style block is optimized for PDF generation */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .report-box {
            width: 98%;
            border: 1px solid #000;
            padding: 5px;
            margin: 0 auto;
        }

        .school-header {
            width: 100%;
            height: auto;
            margin-bottom: 5px;
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
            font-size: 18px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 12px;
        }

        .exam-details {
            text-align: right;
        }

        .exam-details h3 {
            font-size: 14px;
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
            padding: 3px;
            /* Slightly more padding for readability */
            text-align: center;
            font-size: 10px;
            /* Smaller font for table content */
        }

        .marks-table th {
            font-size: 11px;
            background-color: #f2f2f2;
        }

        .marks-table td:first-child {
            text-align: left;
        }

        .section-title {
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 3px;
            text-decoration: underline;
        }

        .comment-box {
            border: 1px solid #000;
            height: 20px;
            padding: 5px;
        }

        .footer-signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            /* More space for signatures */
            margin-bottom: 10px;
        }

        .footer-signatures-table td {
            width: 50%;
        }

        .footer-signatures-table td span {
            display: inline-block;
            border-top: 1px solid #000;
            padding-top: 5px;
            min-width: 150px;
            /* Give the signature line a minimum width */
        }

        .teacher-signature {
            text-align: left;
        }

        .principal-signature {
            text-align: right;
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
                            {{-- Use public_path() for PDF image embedding --}}
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
        $finalResult = 'Pass';

        // --- DYNAMIC LOGIC FOR COLUMNS ---
        $calculatedMarksColspan = ($hasClassTest ? 1 : 0) + ($hasOtherMarks ? 1 : 0);
        @endphp

        <div class="section-title">Academic Performance</div>
        <table class="marks-table">
            <thead>
                <tr>
                    <th rowspan="2">Subject</th>
                    <th rowspan="2">Full Mark</th>
                    <th colspan="{{ count($markdistributions) }}">Obtained Marks</th>

                    {{-- Conditionally render the "Calculated Marks" main header --}}
                    @if ($calculatedMarksColspan > 0)
                    <th colspan="{{ $calculatedMarksColspan }}">Calculated Marks</th>
                    @endif

                    <th rowspan="2">Total</th>
                    <th rowspan="2">Highest</th>
                    <th rowspan="2">Grade</th>
                    <th rowspan="2">GPA</th>
                    <th rowspan="2">Result</th>
                </tr>
                <tr>
                    @foreach ($markdistributions as $distribution)
                    <th>{{ $distribution->name }}</th>
                    @endforeach

                    {{-- Conditionally render the "Calculated Marks" sub-headers --}}
                    @if ($hasClassTest)
                    <th>Class Test</th>
                    @endif
                    @if ($hasOtherMarks)
                    <th>Total</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($marks as $mark)
                @php
                if($mark['fail_any_distribution']){ $finalResult = 'Fail'; }
                @endphp
                <tr>
                    <td>{{ $mark['subject_name'] }}</td>
                    <td>{{ $mark['full_mark'] }}</td>
                    @foreach ($mark['obtained_marks'] as $obtainedMark)
                    <td>{!! $obtainedMark !!}</td>
                    @endforeach

                    {{-- Conditionally render the calculated marks data cells --}}
                    @if ($hasClassTest)
                    <td>{{ $mark['class_test_result'] }}</td>
                    @endif
                    @if ($hasOtherMarks)
                    <td>{{ $mark['other_parts_total'] }}</td>
                    @endif

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
                @php
                if($fourthSubjectMarks['fail_any_distribution']){ $finalResult = 'Fail'; }
                @endphp
                <tr style="background-color: #f0f0f0;">
                    <td>{{ $fourthSubjectMarks['subject_name'] }} (4th Subject)</td>
                    <td>{{ $fourthSubjectMarks['full_mark'] }}</td>
                    @foreach ($fourthSubjectMarks['obtained_marks'] as $obtainedMark)
                    <td>{!! $obtainedMark !!}</td>
                    @endforeach

                    {{-- Conditionally render the calculated marks for 4th subject --}}
                    @if ($hasClassTest)
                    <td>{{ $fourthSubjectMarks['class_test_result'] }}</td>
                    @endif
                    @if ($hasOtherMarks)
                    <td>{{ $fourthSubjectMarks['other_parts_total'] }}</td>
                    @endif

                    <td>{{ $fourthSubjectMarks['total_calculated_marks'] }}</td>
                    <td>{{ $fourthSubjectMarks['highest_mark'] }}</td>
                    <td>{{ $fourthSubjectMarks['grade_name'] }}</td>
                    <td>{{ $fourthSubjectMarks['grade_point'] }}</td>
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
        $finalGrade = \App\Models\Grade::where('start_marks', '<=', ($finalgpa * 20))
            ->where('end_marks', '>=', ($finalgpa * 20))
            ->where('grading_scale', 100)
            ->first();
            $letterGrade = $finalGrade ? $finalGrade->grade_name : 'N/A';

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
                    <td><strong>GPA:</strong> {{ number_format($finalgpa, 2) }}</td>
                    <td>
                        <strong>Result:</strong>
                        {!! $finalResult === 'Fail' ? '<span style="color:red;">Fail</span>' : 'Pass' !!}
                    </td>
                    {{-- Use the pre-calculated position variable passed to this view --}}
                    <td><strong>Position in Class:</strong> {{ $classPosition }}</td>
                </tr>
            </table>

            <div class="section-title" style="margin-bottom: 5px;">Class Teacher's Comment</div>
            <div class="comment-box"></div>

            <table class="info-table">
                <tr>
                    <td><strong>Period:</strong> {{ \Carbon\Carbon::parse($exam->start_at)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($exam->end_at)->format('d M, Y') }}</td>
                    <td><strong>Published Date:</strong> {{ \Carbon\Carbon::now()->format('d M, Y') }}</td>
                </tr>
            </table>

            <table class="footer-signatures-table">
                <tr>
                    <td class="teacher-signature">
                        <span>Class Teacher</span>
                    </td>
                    <td class="principal-signature">
                        <span>Principal</span>
                    </td>
                </tr>
            </table>
    </div>

</body>

</html>