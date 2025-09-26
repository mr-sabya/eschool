<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold;">Financial Report</th>
        </tr>
        <tr>
            <td colspan="2">
                Report Type: {{ ucfirst($data['reportType']) }}
                @if ($data['reportType'] === 'daily')
                | Date: {{ $data['date'] }}
                @elseif ($data['reportType'] === 'monthly')
                | Month: {{ \Carbon\Carbon::parse($data['month'])->format('F Y') }}
                @elseif ($data['reportType'] === 'yearly')
                | Year: {{ $data['year'] }}
                @endif
            </td>
        </tr>
        <tr></tr> {{-- Spacer row --}}
    </thead>
    <tbody>
        {{-- Income Details --}}
        <tr>
            <td style="font-weight: bold;">Income Breakdown</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Payment Method</td>
            <td style="font-weight: bold;">Amount</td>
        </tr>
        @foreach ($data['incomeDetails'] as $detail)
        <tr>
            <td>{{ $detail['payment_method_name'] }}</td>
            <td>{{ $detail['total_amount'] }}</td>
        </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;">Total Income</td>
            <td style="font-weight: bold;">{{ $data['income'] }}</td>
        </tr>
        <tr></tr> {{-- Spacer row --}}

        {{-- Expense Details --}}
        <tr>
            <td style="font-weight: bold;">Expense Breakdown</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Payment Method</td>
            <td style="font-weight: bold;">Amount</td>
        </tr>
        @foreach ($data['expenseDetails'] as $detail)
        <tr>
            <td>{{ $detail['payment_method_name'] }}</td>
            <td>{{ $detail['total_amount'] }}</td>
        </tr>
        @endforeach
        <tr>
            <td style="font-weight: bold;">Total Expense</td>
            <td style="font-weight: bold;">{{ $data['expense'] }}</td>
        </tr>
        <tr></tr> {{-- Spacer row --}}

        {{-- Summary --}}
        <tr>
            <td style="font-weight: bold;">Total Balance</td>
            <td style="font-weight: bold;">{{ $data['total'] }}</td>
        </tr>
    </tbody>
</table>