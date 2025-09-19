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
        }

        @page {
            margin: 20px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #000;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #333;
            padding: 10px 3px;
            text-align: center;
            vertical-align: middle;
        }

        .main-table th {
            font-weight: bold;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
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
            white-space: nowrap;
        }

        .fail-text,
        .fail-row {
            color: red !important;
            font-weight: bold;
        }

        .font-bold {
            font-weight: bold;
        }

        /* --- NEW STYLES FOR VERTICAL TEXT --- */
        .vertical-header {
            height: 70px;
            /* Sets the fixed height for the header cell */
            padding: 0;
            /* The cell itself has no padding */
            position: relative;
            /* Establishes a positioning context for the div */
        }

        .vertical-header .vertical-text {
            position: absolute;
            /* Lifts the div out of the normal flow */

            /* The Centering Trick: */
            left: 50%;
            /* Moves the div's left edge to the middle of the cell */
            transform: translateX(-40%) rotate(-90deg);
            /* Shifts the div back by half its own width, then rotates */

            transform-origin: center bottom;
            /* Rotates "up" from the bottom edge */
            white-space: nowrap;
            font-size: 8px;
            bottom: 50%;
            /* A small margin from the bottom of the cell */
        }
    </style>
</head>

<body>

    @if(count($results) > 0)
    @php
    $resultChunks = collect($results)->chunk(7);
    @endphp

    @foreach($resultChunks as $pageIndex => $chunk)
    <table class="main-table">
        <thead>
            @php
            $dist_count = count($markDistributions);
            $subject_colspan = $dist_count + 2;
            $subjects_count = count($subjects);
            $total_subject_cols = $subjects_count * $subject_colspan;
            $total_columns = 4 + $total_subject_cols + 4;
            @endphp

            <!-- Top Header Rows (Inside the single table) -->
            <tr>
                <td colspan="{{ (int)($total_columns * 0.25) }}"><strong>Total Student:</strong> {{ $totalStudents }}</td>
                <td colspan="{{ (int)($total_columns * 0.25) }}"><strong>Class:</strong> {{ $class->name }}</td>
                <td colspan="{{ (int)($total_columns * 0.5) }}" rowspan="3" class="school-name" style="vertical-align: middle;">
                    Khalishpur Collegiate Girls' School
                </td>
            </tr>
            <tr>
                <td><strong>Total Pass and Fail:</strong> {{ $totalPass }} / {{ $totalFail }}</td>
                <td><strong>Section:</strong> {{ $section->name }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Percentage of Pass:</strong> {{ $passPercentage }}%</td>
            </tr>
            <tr>
                <td colspan="{{ $total_columns / 2 }}" class="exam-title" style="border-right: none;">Annual Exam '{{ \Carbon\Carbon::parse($exam->start_at)->format('y') }} (primary)</td>
                <td colspan="{{ $total_columns / 2 }}" class="sheet-title" style="border-left: none;">Tabulation Sheet</td>
            </tr>

            <!-- Main Column Headers with NEW vertical text method -->
            <tr>
                <th rowspan="2" style="width: 2%;">SL. No.</th>
                <th rowspan="2" style="width: 2%;">ID</th>
                <th rowspan="2" style="width: 2%;">Roll No</th>
                <th rowspan="2" style="width: 8%;">Student Name</th>
                @foreach($subjects as $subject)
                <th colspan="{{ $subject_colspan }}">{{ $subject->subject->name }}</th>
                @endforeach
                <th rowspan="2" class="vertical-header" style="width: 3%;">
                    <div class="vertical-text">Total Mark</div>
                </th>
                <th rowspan="2" class="vertical-header" style="width: 3%;">
                    <div class="vertical-text">Result</div>
                </th>
                <th colspan="2">Position</th>
            </tr>
            <tr>
                @foreach($subjects as $subject)
                <th colspan="{{ $dist_count }}">Hall Mark</th>
                <th rowspan="2" class="vertical-header">
                    <div class="vertical-text">Total</div>
                </th>
                <th rowspan="2" class="vertical-header">
                    <div class="vertical-text">Grade Point</div>
                </th>
                @endforeach
                <th rowspan="2" class="vertical-header">
                    <div class="vertical-text">Section</div>
                </th>
                <th rowspan="2" class="vertical-header">
                    <div class="vertical-text">Class</div>
                </th>
            </tr>
            <tr>
                <th style="border:none;"></th>
                <th style="border:none;"></th>
                <th style="border:none;"></th>
                <th style="border:none;"></th>
                @foreach($subjects as $subject)
                @foreach($markDistributions as $dist)
                <th class="vertical-header">
                    <div class="vertical-text">{{ $dist->name }}</div>
                </th>
                @endforeach
                @endforeach
                <th style="border:none;"></th>
                <th style="border:none;"></th>
                <th style="border:none;"></th>
                <th style="border:none;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($chunk as $result)
            <tr class="{{ $result['is_fail'] ? 'fail-row' : '' }}">
                <td>{{ ($pageIndex * 7) + $loop->iteration }}</td>
                <td>{{ $result['student']->id ?? '' }}</td>
                <td>{{ $result['student']->roll_number }}</td>
                <td class="student-name">{{ $result['student']->user->name }}</td>
                @foreach($subjects as $header_subject)
                @php
                $subjectResult = collect($result['subjects'])->firstWhere('subject_id', $header_subject->subject_id);
                @endphp
                @if($subjectResult)
                @foreach($markDistributions as $dist)
                <td>@php $mark = $subjectResult['obtained_marks_by_distribution'][$dist->id] ?? '-'; @endphp {{ is_null($mark) ? '' : $mark }}</td>
                @endforeach
                <td class="font-bold">{{ round($subjectResult['total_calculated_marks']) }}</td>
                <td class="font-bold">@if($subjectResult['is_fail']) F(Fail) @else {{ $subjectResult['grade_name'] }} @endif</td>
                @else
                @for ($i = 0; $i < $subject_colspan; $i++)<td>
                    </td>@endfor
                    @endif
                    @endforeach
                    <td class="font-bold">{{ round($result['total_marks']) }}</td>
                    <td class="font-bold">@if($result['is_fail']) Fail @else {{ $result['final_grade'] }}({{ number_format($result['final_gpa'], 2) }}) @endif</td>
                    <td class="font-bold">{{ $result['position_in_section'] }}</td>
                    <td class="font-bold">{{ $result['position_in_class'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <table style="width: 100%; margin-top: 30px;">
        <tbody>
            <tr>
                <td style="text-align: left; border-top: 1px solid #000; padding-top: 5px; font-weight: bold;">Class Teacher</td>
                <td style="text-align: right; border-top: 1px solid #000; padding-top: 5px; font-weight: bold;">Principal</td>
            </tr>
        </tbody>
    </table>

    @endforeach
    @else
    <p style="text-align: center; padding: 50px;">No students found for the selected criteria.</p>
    @endif
</body>

</html>