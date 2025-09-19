<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Merit List</title>
    <style>
        body {
            font-family: 'dejavu sans', sans-serif;
            font-size: 12px;
        }

        .header-table,
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td,
        .main-table td,
        .main-table th {
            border: 1px solid #000;
            padding: 6px;
        }

        .header-table td {
            border: 1px solid #777;
            padding: 4px 8px;
        }

        .main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .main-table td {
            text-align: center;
        }

        .main-table td.name {
            text-align: left;
        }

        .header {
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 style="text-align: center; margin: 0; padding: 0;">Merit List</h2>
        <table class="header-table">
            <tr>
                <td><strong>Program/Class:</strong> {{ $class->name ?? 'N/A' }}</td>
                <td><strong>Group:</strong> {{ $department->name ?? 'General' }}</td>
                <td><strong>Section:</strong> {{ $section->name ?? 'General' }}</td>
            </tr>
            <tr>
                <td><strong>Shift:</strong> MORNING</td>
                <td><strong>Version:</strong> Bangla</td>
                <td><strong>Session:</strong> {{ $session->name ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Std ID</th>
                <th>Student Name</th>
                <th>Roll</th>
                <th>Letter Grade</th>
                <th>GPA</th>
                <th>Total Mark</th>
                <th>Result</th>
                <th>Merit Position</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $res)
            {{-- Skip failed students from the merit list view --}}
            @if($res['final_result'] === 'Fail')
            @continue
            @endif
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $res['student']->id ?? 'N/A' }}</td>
                <td class="name">{{ $res['student']->user->name ?? 'N/A' }}</td>
                <td>{{ $res['student']->roll_number }}</td>
                <td>{{ $res['letter_grade'] }}</td>
                <td>{{ number_format($res['gpa'], 2) }}</td>
                <td>{{ number_format($res['total_marks'], 2) }}</td>
                <td>{{ $res['final_result'] }}</td>
                <td>
                    {{ $res['position'] . \App\Helpers\NumberHelper::getOrdinalSuffix($res['position']) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>