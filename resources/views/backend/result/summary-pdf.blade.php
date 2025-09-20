<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Result Summary</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 25px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            color: #2c3e50;
        }

        .header h3 {
            margin: 5px 0;
            font-weight: 300;
        }

        .header p {
            font-size: 14px;
            color: #555;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .summary-table th {
            background-color: #f7f7f7;
            font-weight: bold;
        }

        .summary-table td:last-child {
            text-align: center;
            font-weight: bold;
        }

        .table-title {
            font-size: 16px;
            font-weight: bold;
            padding: 10px 0 5px 0;
        }

        .school-info {
            text-align: center;
            margin-bottom: 10px;
        }
        .school-info .logo {
            width: 100%;
            text-align: center;
        }

        .school-info .logo img {
            width: 120px;
            max-width: 120px;
            height: auto;
        } 
    </style>
</head>

<body>

    <div class="school-info">
        <div class="logo">
            <img src="{{ public_path('assets/frontend/images/kcgs-logo.png') }}" alt="School Logo">
        </div>
        <h1 style="text-align: center; margin: 0; padding: 0;">{{ $settings->school_name ?? 'School Name' }}</h1>
        <p style="text-align: center; margin: 0; padding: 0;">{{ $settings->address ?? 'School Address' }}</p>
        <p style="text-align: center; margin: 0; padding: 0;">Phone: {{ $settings->phone ?? 'School Phone' }}, Email: {{ $settings->email ?? 'School Email' }}</p>
    </div>
    
    <div class="header">
        <p>
            <strong>Exam:</strong> {{ $exam->examCategory->name ?? 'N/A' }} <br>
            <strong>Session:</strong> {{ $session->name ?? 'N/A' }} |
            <strong>Class:</strong> {{ $class->name ?? 'N/A' }} |
            <strong>Section:</strong> {{ $section->name ?? 'N/A' }}
        </p>
    </div>

    <div class="table-title">Overall Performance</div>
    <table class="summary-table">
        <tbody>
            <tr>
                <td>Total Students</td>
                <td>{{ $summary['total_students'] }}</td>
            </tr>
            <tr>
                <td>Students Passed</td>
                <td>{{ $summary['passed_students'] }}</td>
            </tr>
            <tr>
                <td>Students Failed</td>
                <td>{{ $summary['failed_students'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="table-title">Grade Distribution</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Grade</th>
                <th>Number of Students</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary['grades'] as $grade => $count)
            <tr>
                <td>{{ $grade }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>