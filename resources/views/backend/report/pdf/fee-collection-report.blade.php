<!DOCTYPE html>
<html>

<head>
    <title>Fee Collection Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 9px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Fee Collection Report</h1>
    <p>
        <strong>Period:</strong>
        @if ($data['reportType'] === 'daily') {{ $data['date'] }}
        @elseif ($data['reportType'] === 'monthly') {{ \Carbon\Carbon::parse($data['month'])->format('F Y') }}
        @elseif ($data['reportType'] === 'yearly') {{ $data['year'] }}
        @elseif ($data['reportType'] === 'custom') From {{ $data['fromDate'] }} To {{ $data['toDate'] }}
        @endif
    </p>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Student</th>
                <th>Class</th>
                <th>Fee Type</th>
                <th class="text-right">Amount Paid</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Fine</th>
                <th>Method</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['feeCollections'] as $collection)
            <tr>
                <td>{{ \Carbon\Carbon::parse($collection->payment_date)->format('Y-m-d') }}</td>
                <td>{{ optional($collection->student)->name }}</td>
                <td>{{ optional($collection->schoolClass)->name }} {{ optional($collection->classSection)->name }}</td>
                <td>{{ optional($collection->feeList->feeType)->name }}</td>
                <td class="text-right">{{ number_format($collection->amount_paid, 2) }}</td>
                <td class="text-right">{{ number_format($collection->discount, 2) }}</td>
                <td class="text-right">{{ number_format($collection->fine, 2) }}</td>
                <td>{{ optional($collection->paymentMethod)->name }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">Total:</td>
                <td class="text-right">{{ number_format($data['totalAmountCollected'], 2) }}</td>
                <td class="text-right">{{ number_format($data['totalDiscount'], 2) }}</td>
                <td class="text-right">{{ number_format($data['totalFine'], 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>