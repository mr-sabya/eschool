<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tabulation Sheet</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        h2,
        h4 {
            margin: 2px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Tabulation Sheet</h2>
    <h4>{{ $exam->examCategory->name }} - {{ $exam->academicSession->name }}</h4>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Roll</th>
                @foreach($subjects as $sub)
                <th>{{ $sub->subject->name }}</th>
                @endforeach
                <th>Total</th>
                <th>GPA</th>
                <th>Grade</th>
                <th>Position</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            @php
            $studentResult = App\Helpers\ClassPositionHelper::getStudentPosition(
            $student->id, $students, $exam->id
            );
            @endphp
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $student->id }}</td>
                <td style="text-align: left">{{ $student->user->name }}</td>
                <td>{{ $student->roll_number }}</td>

                @foreach($subjects as $sub)
                @php
                $marks = App\Helpers\SchoolHighestMarkHelper::getStudentSubjectMarks(
                $student->id, $sub->subject_id, $classId, $sectionId, $exam->id
                );
                @endphp
                <td>{{ $marks['total'] ?? '-' }}</td>
                @endforeach

                <td>{{ $studentResult['total'] }}</td>
                <td>{{ number_format($studentResult['gpa'], 2) }}</td>
                <td>{{ $studentResult['grade'] }}</td>
                <td>{{ $studentResult['position'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>