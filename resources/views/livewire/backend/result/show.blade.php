<div class="report-box">
    <div class="school-header">
        <img src="{{ url('assets/frontend/images/kcgs-logo.png') }}" alt="School Logo">
        <div class="title">Khalishpur Collegiate Girls' School</div>
        <div class="subtitle">Khalishpur, Khulna</div>
        <div class="subtitle">Phone: 02477700262, kcgs899@gmail.com</div>
        <h3>{{ $exam->examCategory['name'] }}-{{ $exam->academicSession['name'] }}<br>Progress Report</h3>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Name:</strong> {{ $student->user['name'] }}</td>
            <td><strong>Student's ID:</strong> {{ $data['student']['id'] }}</td>
            <td><strong>Class:</strong> {{ $student->schoolClass['name'] }}</td>
            <td><strong>Section:</strong> {{ $student->classSection['name'] }}</td>
            <td><strong>Roll No:</strong> {{ $student['roll_number'] }}</td>
        </tr>
    </table>

    <div class="section-title">Academic Performance</div>
    <table class="marks-table">
        <thead>
            <tr>
                <th rowspan="2">Subject</th>
                <th rowspan="2">Full Mark</th>
                <th colspan="2">Obtained Marks</th>
                <th colspan="2">Calculated Marks</th>
                <th rowspan="2">Total</th>
                <th rowspan="2">Highest</th>
                <th rowspan="2">GPA</th>
                <th rowspan="2">Grade</th>
                <th rowspan="2">Result</th>
            </tr>
            <tr>
                <th>CT</th>
                <th>Annual</th>
                <th>CT</th>
                <th>Annual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject)
            <tr>
                <td>{{ $subject['name'] }}</td>
                <td>{{ $subject['full_mark'] }}</td>
                <td>{{ $subject['ct'] }}</td>
                <td>{{ $subject['annual'] }}</td>
                <td>{{ $subject['cal_ct'] }}</td>
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