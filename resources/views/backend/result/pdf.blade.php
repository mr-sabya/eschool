<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Progress Report - {{ $student['name'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .report-box {
            max-width: 1000px;
            margin: auto;
            border: 1px solid #000;
            padding: 10px;
        }

        .school-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .school-header img {
            height: 80px;
        }

        .title {
            font-size: 18px;
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
            margin-top: 15px;
            text-decoration: underline;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature {
            text-align: center;
            width: 200px;
            border-top: 1px solid #000;
        }

        .comment-box {
            border: 1px solid #000;
            height: 50px;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="report-box">
        <div class="school-header">
            <img src="{{ public_path('logo.png') }}" alt="School Logo">
            <div class="title">Khalishpur Collegiate Girls' School</div>
            <div class="subtitle">Khalishpur, Khulna</div>
            <div class="subtitle">Phone: 02477700262, kcgs899@gmail.com</div>
            <h3>Annual Exam-2024<br>Progress Report</h3>
        </div>

        <table class="info-table">
            <tr>
                <td><strong>Name:</strong> {{ $student['name'] }}</td>
                <td><strong>Student's ID:</strong> {{ $student['id'] }}</td>
                <td><strong>Class:</strong> {{ $student['class'] }}</td>
                <td><strong>Section:</strong> {{ $student['section'] }}</td>
                <td><strong>Roll No:</strong> {{ $student['roll'] }}</td>
            </tr>
        </table>

        <div class="section-title">Academic Performance</div>
        <table class="marks-table">
            <thead>
                <tr>
                    <th rowspan="2">Subject</th>
                    <th rowspan="2">Full Mark</th>
                    <th colspan="4">Obtained Marks</th>
                    <th colspan="4">Calculated Marks</th>
                    <th rowspan="2">Total</th>
                    <th rowspan="2">Highest</th>
                    <th rowspan="2">GPA</th>
                    <th rowspan="2">Grade</th>
                    <th rowspan="2">Result</th>
                </tr>
                <tr>
                    <th>CT</th>
                    <th>Assn-1</th>
                    <th>Assn-2</th>
                    <th>Annual</th>
                    <th>CT</th>
                    <th>Assn-1</th>
                    <th>Assn-2</th>
                    <th>Annual</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subjects as $subject)
                <tr>
                    <td>{{ $subject['name'] }}</td>
                    <td>{{ $subject['full_mark'] }}</td>
                    <td>{{ $subject['ct'] }}</td>
                    <td>{{ $subject['a1'] }}</td>
                    <td>{{ $subject['a2'] }}</td>
                    <td>{{ $subject['annual'] }}</td>
                    <td>{{ $subject['cal_ct'] }}</td>
                    <td>{{ $subject['cal_a1'] }}</td>
                    <td>{{ $subject['cal_a2'] }}</td>
                    <td>{{ $subject['cal_annual'] }}</td>
                    <td>{{ $subject['total'] }}</td>
                    <td>{{ $subject['highest'] }}</td>
                    <td>{{ $subject['gpa'] }}</td>
                    <td>{{ $subject['grade'] }}</td>
                    <td>{{ $subject['result'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="info-table">
            <tr>
                <td><strong>Obtained Total:</strong> {{ $summary['total'] }}</td>
                <td><strong>Letter Grade:</strong> {{ $summary['grade'] }}</td>
                <td><strong>GPA:</strong> {{ $summary['gpa'] }}</td>
                <td><strong>Result:</strong> {{ $summary['result'] }}</td>
                <td><strong>Position in Class:</strong> {{ $summary['position'] }}</td>
            </tr>
        </table>

        <div class="section-title">Class Teacher's Comment</div>
        <div class="comment-box">{{ $summary['comment'] }}</div>

        <table class="info-table">
            <tr>
                <td><strong>Period:</strong> 21/11/2024 - 05/12/2024</td>
                <td><strong>Published Date:</strong> 31/12/2024</td>
            </tr>
        </table>

        <div class="footer">
            <div class="signature">Class Teacher</div>
            <div class="signature">Principal</div>
        </div>
    </div>
</body>

</html>