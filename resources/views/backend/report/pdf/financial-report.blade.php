<!DOCTYPE html>
<html>

<head>
    <title>Financial Ledger Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 6px;
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
    <h1>Financial Ledger Report</h1>
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
                <th>Particulars</th>
                <th class="text-right">Income</th>
                <th class="text-right">Expense</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['ledgerDetails'] as $entry)
            <tr>
                <td>{{ $entry['date'] }}</td>
                <td>{{ $entry['description'] }}</td>
                <td class="text-right">{{ $entry['income'] > 0 ? number_format($entry['income'], 2) : '-' }}</td>
                <td class="text-right">{{ $entry['expense'] > 0 ? number_format($entry['expense'], 2) : '-' }}</td>
                <td class="text-right">{{ number_format($entry['balance'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" class="text-right">Total:</td>
                <td class="text-right">{{ number_format($data['totalIncome'], 2) }}</td>
                <td class="text-right">{{ number_format($data['totalExpense'], 2) }}</td>
                <td class="text-right">{{ number_format($data['netBalance'], 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>