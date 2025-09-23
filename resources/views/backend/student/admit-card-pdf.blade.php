<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Cards</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
        }

        .admit-card {
            border: 2px solid #000;
            padding: 15px;
            margin-bottom: 20px;
            position: relative;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            text-align: center;

            padding-bottom: 10px;
        }

        .header-border {
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
            width: 80%
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
        }

        .exam-name {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        .student-info {
            margin-top: 20px;
            width: 80%;
        }

        .image-box {
            width: 100px;
            height: 120px;
            border: 1px solid #ccc;
            position: absolute;
            top: 80px;
            right: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            /* position: absolute; */
            bottom: 20px;
            width: 95%;
        }

        .signature {
            /* float: right; */

            margin-top: 40px;
        }

        .signature strong {
            position: relative;
            display: inline-block;
            padding-top: 10px;
        }

        .signature strong::after {
            content: "";
            position: absolute;
            top: -2px;
            left: 0;
            width: 100%;
            border-bottom: 1px solid #000;
        }
    </style>
</head>

<body>

    @foreach ($students as $student)
    <div class="admit-card">
        <div class="header">
            <div class="school-name">{{ $settings->school_name ?? 'School Name'}}</div>
            <div>{{ $settings->school_address }}</div>
            <div class="exam-name">ADMIT CARD - {{ $exam->examCategory['name'] }} ({{ $exam->academicSession->name }})</div>
        </div>
        <div class="header-border"></div>
        <div class="image-box">
            @if ($student->profile_picture && file_exists(public_path('storage/' . $student->profile_picture)))
            <img src="{{ public_path('storage/' . $student->profile_picture) }}" class="student-photo" alt="Photo">
            @else
            {{-- If no photo, display a styled placeholder box --}}
            <div class="student-photo" style="text-align: center; padding-top: 50px; background-color: #f0f0f0;">
                No Photo
            </div>
            @endif
        </div>

        <div class="student-info">
            <table>
                <tr>
                    <td style="width: 20%;"><strong>Student Name:</strong></td>
                    <td style="width: 50%;">{{ $student->user->name }}</td>
                    <td style="width: 15%;"><strong>Roll No:</strong></td>
                    <td style="width: 15%;">{{ $student->roll_number }}</td>
                </tr>
                <tr>
                    <td><strong>Class:</strong></td>
                    <td>{{ $student->schoolClass->name }} ({{ $student->classSection->name }})</td>
                    <td><strong>Student ID:</strong></td>
                    <td>{{ $student->id_no }}</td>
                </tr>
                @if($student->department)
                <tr>
                    <td><strong>Department:</strong></td>
                    <td colspan="3">{{ $student->department->name }}</td>
                </tr>
                @endif
            </table>
        </div>

        <h4 style="text-align:center; margin-top: 20px;">Exam Schedule</h4>
        <table>
            <thead>
                <tr>
                    <th>Date & Day</th>
                    <th>Subject</th>
                    <th>Time</th>
                    <th>Room</th>
                </tr>
            </thead>
            <tbody>
                @php
                // Filter routines for this specific student's section/department
                $studentRoutines = $examRoutines->where('class_section_id', $student->class_section_id)
                ->where('department_id', $student->department_id);
                @endphp
                @forelse ($studentRoutines as $routine)
                <tr>
                    <td>{{ $routine->exam_date->format('d-m-Y') }} <br> ({{ $routine->exam_date->format('l') }})</td>
                    <td>{{ $routine->subject->name }}</td>
                    <td>{{ $routine->timeSlot->start_time->format('h:i A') }} - {{ $routine->timeSlot->end_time->format('h:i A') }}</td>
                    <td>{{ $routine->classRoom->name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;">No schedule found for this section.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Instructions:</strong></p>
            <ol style="font-size: 10px; padding-left: 15px;">
                <li>Students must carry this admit card to the examination hall.</li>
                <li>Reporting time is 30 minutes before the commencement of the exam.</li>
                <li>No electronic devices are allowed in the examination hall.</li>
            </ol>
            <div class="signature">
                <strong>Principal's Signature</strong>
            </div>
        </div>
    </div>

    {{-- Add a page break unless it's the last student --}}
    @if (!$loop->last)
    <div class="page-break"></div>
    @endif
    @endforeach

</body>

</html>