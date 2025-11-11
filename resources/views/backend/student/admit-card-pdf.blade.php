<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Cards</title>
    <style>
        @page {
            /* margin: 15px; */
            /* Standard page margin for printing */
        }

        body {
            font-family: Arial, sans-serif;
            /* Switched to Arial for better resemblance to the image */
            font-size: 11px;
        }

        /* The main table for the 2x2 grid layout */
        .layout-table {
            width: 100%;
            border-collapse: collapse;
            /* This is crucial for creating gaps */
            /* border-spacing: 15px; */
            /* This creates the horizontal and vertical gap between cards */
        }

        .card-cell {
            width: 50%;
            padding: 0;
            /* Padding is no longer needed here */
            vertical-align: top;
            padding: 20px 30px;
        }

        .admit-card .card {
            border: 2px solid #000;
            padding: 15px;
            box-sizing: border-box;
            width: 100%;
            position: relative;
            page-break-inside: avoid;
            /* Prevents card from splitting during printing */
        }

        .header {
            text-align: center;
            padding-bottom: 5px;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
        }

        .school-address {
            font-size: 12px;
            margin-bottom: 8px;
        }

        .exam-name {
            display: inline-block;
            /* Allows border to wrap the text */
            font-size: 14px;
            font-weight: bold;
            margin-top: 3px;
            padding-bottom: 4px;
            border-bottom: 2px solid #000;
            /* Underline effect for the title */
        }

        .student-info {
            margin-top: 15px;
            width: 100%;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .student-info th,
        .student-info td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .student-info td:first-child {
            /* Targets 'Student Name:' and 'Class:' cells */
            font-weight: bold;
            width: 25%;
        }

        .footer {
            width: 100%;
            margin-top: 15px;
            font-size: 11px;
        }

        .footer p {
            margin: 0 0 5px 0;
        }

        .footer ol {
            padding-left: 15px;
            margin: 0;
        }

        .signature {
            margin-top: 30px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-bottom: 4px;
        }

        /* This class forces a page break after the table row */
        .page-break-row {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <table class="layout-table">
        @foreach ($students as $index => $student)

        {{-- Start a new table row for every first card in a pair (index 0, 2, 4...) --}}
        @if ($index % 2 == 0)
        <tr>
            @endif

            <td class="card-cell">
                <div class="admit-card">
                    <div class="card">
                        <div class="header">
                            <div class="school-name">{{ $settings->school_name ?? 'School Name'}}</div>
                            <div class="school-address">{{ $settings->school_address ?? 'School Address' }}</div>
                            <div class="exam-name">ADMIT CARD - {{ $exam->examCategory['name'] }} ({{ $exam->academicSession->name }})</div>
                        </div>

                        <div class="student-info">
                            <table>
                                <tr>
                                    <td><strong>Student Name:</strong></td>
                                    <td>{{ $student->user->name }}</td>
                                    <td style="font-weight: bold; width: 15%;"><strong>Roll No:</strong></td>
                                    <td style="width: 15%;">{{ $student->roll_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Class:</strong></td>
                                    <td>{{ $student->schoolClass->name }} ({{ $student->classSection->name }})</td>
                                    <td style="font-weight: bold;"><strong>Student ID:</strong></td>
                                    <td>{{ $student->id }}</td>
                                </tr>
                                @if($student->department)
                                <tr>
                                    <td><strong>Department:</strong></td>
                                    <td colspan="3">{{ $student->department->name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <div class="footer">
                            <p><strong>Instructions:</strong></p>
                            <ol>
                                <li>Students must carry this admit card to the examination hall.</li>
                                <li>Reporting time is 30 minutes before the commencement of the exam.</li>
                                <li>No electronic devices are allowed in the examination hall.</li>
                            </ol>
                            <div class="signature">
                                <div class="signature-line"></div>
                                <strong>Principal's Signature</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </td>

            {{-- End the table row after every second card (index 1, 3, 5...) or if it's the last student --}}
            @if (($index + 1) % 2 == 0 || $loop->last)
        </tr>
        @endif

        {{-- After every 4th card, insert a row that will act as a page-break --}}
        @if (($index + 1) % 4 == 0 && !$loop->last)
        <tr class="page-break-row">
            <td colspan="2"></td>
        </tr>
        @endif

        @endforeach
    </table>
</body>

</html>