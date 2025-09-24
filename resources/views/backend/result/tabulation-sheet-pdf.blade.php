<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tabulation Sheet</title>
    <style>
        @media print {
            thead {
                display: table-header-group;
            }

            tbody {
                display: table-row-group;
            }

            tr {
                page-break-inside: avoid;
            }

            .page-break {
                page-break-after: always;
            }

            .signature-table {
                break-after: page;
                /* Modern standard */
                page-break-after: always;
            }
        }

        @page {
            margin: 20px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
        }

        /* --- START: HEADER TABLE STYLES (NO FLEXBOX) --- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .header-table>tbody>tr>td {
            padding: 0;
            vertical-align: top;
            border-left: 1px solid #000;
        }

        .header-table>tbody>tr>td:first-child {
            border-left: 0;
        }

        .nested-table {
            width: 100%;
            border-collapse: collapse;
        }

        .nested-table td {
            border: 1px solid #333;
            padding: 4px 8px;
            text-align: center;
        }

        .stats-section .label {
            text-align: left;
            padding-left: 10px;
        }

        .school-details {
            text-align: center;
            padding: 4px;
        }

        .school-details .school-name {
            font-weight: bold;
            font-size: 1.5em;
            padding: 2px;
            border-bottom: 1px solid #000;
        }

        .school-details .exam-name {
            font-size: 1.2em;
            padding: 4px;
            border-bottom: 1px solid #000;
            margin-bottom: 4px;
        }

        .school-details .sheet-title {
            padding: 2px;
            display: inline-block;
            width: 50%;
            margin: 0 auto;
        }

        .dates-cell {
            padding: 8px 10px;
            border-right: 1px solid #000;
            vertical-align: middle;
        }

        .dates-cell span {
            display: block;
        }

        .dates-cell span:first-child {
            margin-bottom: 20px;
        }

        /* --- END: HEADER TABLE STYLES --- */


        /* main table */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            border-top: 0;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #333;
            padding: 8px 3px;
            text-align: center;
            vertical-align: middle;
        }

        .main-table th {
            font-weight: bold;
        }

        .exam-title {
            font-size: 14px;
            text-align: center;
            font-weight: bold;
        }

        .sheet-title {
            font-size: 12px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }

        .student-name {
            text-align: left !important;
            padding-left: 3px !important;
            /* white-space: nowrap; */
        }

        .fail-text,
        .fail-row {
            color: red !important;
            /* font-weight: bold; */
        }

        .font-bold {
            /* font-weight: bold; */
        }

        /* --- STYLES FOR VERTICAL TEXT --- */
        .vertical-header {
            height: 50px;
            padding: 0;
            position: relative;
        }

        .vertical-header .vertical-text {
            position: absolute;
            left: 50%;
            transform: translateX(-40%) rotate(-90deg);
            transform-origin: center bottom;
            white-space: nowrap;
            font-size: 8px;
            bottom: 50%;
        }

        .fixed-height-row td,
        .fixed-height-row th {
            height: 35px;
            vertical-align: middle;
            /* Or top, bottom */
        }
    </style>
</head>

<body>

    @if(isset($results) && count($results) > 0)
    @php
    if($subjectsCount > 10){
    $resultChunks = collect($results)->chunk(13); // Adjust chunk size as needed
    }else{
    $resultChunks = collect($results)->chunk(7); // Adjust chunk size as needed
    }
    @endphp

    @foreach($resultChunks as $pageIndex => $chunk)

    <!-- HEADER SECTION -->
    <table class="header-table">
        <tr>
            <!-- Left Section: Stats -->
            <td style="width: 25%;">
                <table class="nested-table stats-section">
                    <tr style="background-color: #e0e0e0;">
                        <td class="label" style="border-top:0; border-left:0;">Total Student</td>
                        <td style="border-top:0; border-right:0;"><strong>{{ $totalStudents }}</strong></td>
                    </tr>
                    <tr style="background-color: #e0e0e0;">
                        <td class="label" style="border-left:0;">Total Pass and Fail</td>
                        <td style="padding:0; border-right:0;">
                            <table class="nested-table">
                                <tr>
                                    <td style="width: 50%; border:0; border-right: 1px solid #333;"><strong>{{ $totalPass }}</strong></td>
                                    <td style="width: 50%; border:0;"><strong>{{ $totalFail }}</strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="background-color: #e0e0e0;">
                        <td class="label" style="border-left:0; border-bottom:0;">Percentage of Pass</td>
                        <td style="border-right:0; border-bottom:0;"><strong>{{ $passPercentage }}%</strong></td>
                    </tr>
                </table>
            </td>
            <!-- Middle Section: School Info -->
            <td style="width: 40%;">
                <table class="nested-table">
                    <tr>
                        <td style="width: 35%; padding:0; border:0; vertical-align:top;">
                            <table class="nested-table">
                                <tr style="background-color: #e0e0e0;">
                                    <td style="border-top:0; border-left:0;">Class/Section</td>
                                    <td style="border-top:0; border-right:0;">{{ $section->name }}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:0; border-left:0;">{{ $class->name }}</td>
                                    <td style="border-bottom:0; border-right:0;">@if($department){{ $department->name }}@else General @endif</td>
                                </tr>
                            </table>
                        </td>
                        <td class="school-details" style="border:0; border-left:1px solid #000;">
                            <div class="school-name">{{ $settings->school_name ?? 'School Name Not Set' }}</div>
                            <div class="exam-name">{{ $exam->examCategory['name'] }} - {{ $session->name }}</div>
                            <div class="sheet-title">Tabulation Sheet</div>
                        </td>
                    </tr>
                </table>
            </td>
            <!-- Right Section: Grades & Dates -->
            <td style="width: 35%;">
                <table class="nested-table">
                    <tr>
                        <td class="dates-cell" style="width: 80px; border:0; border-right: 1px solid #000;">
                            <span>{{ \Carbon\Carbon::parse($exam->start_at)->format('d-M-Y') }}</span>
                            <span>{{ \Carbon\Carbon::parse($exam->end_at)->format('d-M-Y') }}</span>
                        </td>
                        <td style="padding:0; border:0; vertical-align:top;">
                            <table class="nested-table">
                                <tr style="background-color: #e0e0e0;">
                                    <td style="border-top:0; border-left:0;">A+</td>
                                    <td>A</td>
                                    <td>A-</td>
                                    <td>B</td>
                                    <td>C</td>
                                    <td>F</td>
                                    <td style="border-right:0;">Total</td>
                                </tr>
                                <tr>
                                    <td style="border-left:0;">{{ $gradeCounts['A+'] ?? 0 }}</td>
                                    <td>{{ $gradeCounts['A'] ?? 0 }}</td>
                                    <td>{{ $gradeCounts['A-'] ?? 0 }}</td>
                                    <td>{{ $gradeCounts['B'] ?? 0 }}</td>
                                    <td>{{ $gradeCounts['C'] ?? 0 }}</td>
                                    <td>{{ $gradeCounts['F'] ?? 0 }}</td>
                                    <td style="border-right:0;">{{ $totalStudents }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align:left; border-left:0; border-bottom:0; padding-left: 10px;">Class/Section Highest Number</td>
                                    <td colspan="3" style="border-right:0; border-bottom:0;"><strong>{{ round($highestMark) }}</strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            @php
            // $markDistributions is now the pre-filtered list allowed by the exam.
            $dist_count = count($markDistributions);
            // The number of columns per subject is the count of distributions + 2 (for Total and Grade Point).
            // If there are no distributions, it's just 2.
            $subject_colspan = $dist_count > 0 ? ($dist_count + 2) : 2;
            @endphp

            <!-- Row 1: Main Headers -->
            <tr>
                <th rowspan="3">SL</th>
                <th rowspan="3">ID</th>
                <th rowspan="3">Roll</th>
                <th rowspan="3" style="width: 10%;">Student Name</th>
                @foreach($subjects as $subject)
                <th colspan="{{ $subject_colspan }}">{{ $subject->subject->name }}</th>
                @endforeach
                <th rowspan="3" class="vertical-header">
                    <div class="vertical-text">Total Mark</div>
                </th>
                <th rowspan="3" class="vertical-header">
                    <div class="vertical-text">Result</div>
                </th>
                <th colspan="2" rowspan="2">Position</th>
            </tr>
            <!-- Row 2: Sub Headers -->
            <tr>
                @foreach($subjects as $subject)
                {{-- ============================ MODIFICATION START ============================ --}}
                {{-- Only show the "Hall Mark" header if there are distributions to display for it. --}}
                @if($dist_count > 0)
                <th colspan="{{ $dist_count }}">Hall Mark</th>
                @endif
                {{-- ============================ MODIFICATION END ============================ --}}
                <th rowspan="2" class="vertical-header">
                    <div class="vertical-text">Total</div>
                </th>
                <th rowspan="2" class="vertical-header">
                    <div class="vertical-text">Grade Point</div>
                </th>
                @endforeach
            </tr>
            <!-- Row 3: Vertical Distribution Headers -->
            <tr>
                @foreach($subjects as $subject)
                {{-- This loop now only shows headers for the mark distributions allowed by the exam. --}}
                @foreach($markDistributions as $dist)
                <th class="vertical-header">
                    <div class="vertical-text">{{ $dist->name }}</div>
                </th>
                @endforeach
                @endforeach
                <th>Sec</th>
                <th>Class</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chunk as $result)
            <tr class="fixed-height-row {{ $result['is_fail'] ? 'fail-row' : '' }}">
                <td>{{ ($pageIndex * 7) + $loop->iteration }}</td>
                <td>{{ $result['student']->id ?? '' }}</td>
                <td>{{ $result['student']->roll_number }}</td>
                <td class="student-name">{{ $result['student']->user->name }}</td>
                @foreach($subjects as $header_subject)
                @php $subjectResult = collect($result['subjects'])->firstWhere('subject_id', $header_subject->subject_id); @endphp
                @if($subjectResult)
                {{-- This data loop automatically aligns with the allowed headers --}}
                @foreach($markDistributions as $dist)
                <td>
                    @php $mark = $subjectResult['obtained_marks_by_distribution'][$dist->id] ?? '-'; @endphp
                    {{-- Check for null which means not applicable for this subject --}}
                    {{ is_null($mark) ? '' : $mark }}
                </td>
                @endforeach
                <td class="font-bold">{{ round($subjectResult['total_calculated_marks']) }}</td>
                <td class="font-bold">{{ $subjectResult['grade_name'] }}</td>
                @else
                {{-- Renders empty cells for subjects a student doesn't have --}}
                @for ($i = 0; $i < $subject_colspan; $i++)<td>
                    </td>@endfor
                    @endif
                    @endforeach
                    <td class="font-bold">{{ round($result['total_marks']) }}</td>
                    <td class="font-bold">@if($result['is_fail']) Fail @else {{ $result['final_grade'] }} ({{ number_format($result['final_gpa'], 2) }}) @endif</td>
                    <td class="font-bold">{{ $result['position_in_section'] }}</td>
                    <td class="font-bold">{{ $result['position_in_class'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-table">
        <table style="width: 100%; margin-top: 40px; border-collapse: collapse; border: none; margin-bottom: 20px;">
            <tr>
                <td style="text-align: left; border: none;">
                    {{-- MODIFICATION: Added style for a border-top --}}
                    <span style="border-top: 1px solid #000; padding-top: 2px;">Class Teacher</span>
                </td>
                <td style="text-align: right; border: none;">
                    {{-- MODIFICATION: Added style for a border-top --}}
                    <span style="border-top: 1px solid #000; padding-top: 2px;">Principal</span>
                </td>
            </tr>
        </table>
    </div>

    @if(!$loop->last)
    <div class="page-break"></div>
    @endif

    @endforeach
    @else
    <p style="text-align: center; padding: 50px;">No students found for the selected criteria.</p>
    @endif
</body>

</html>