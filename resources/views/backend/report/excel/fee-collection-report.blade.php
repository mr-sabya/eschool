<table>
    <thead>
        <tr>
            <th colspan="8" style="font-weight: bold;">Fee Collection Report</th>
        </tr>
        <tr>
            <td colspan="8">
                Period:
                @if ($data['reportType'] === 'daily') {{ $data['date'] }}
                @elseif ($data['reportType'] === 'monthly') {{ \Carbon\Carbon::parse($data['month'])->format('F Y') }}
                @elseif ($data['reportType'] === 'yearly') {{ $data['year'] }}
                @elseif ($data['reportType'] === 'custom') From {{ $data['fromDate'] }} To {{ $data['toDate'] }}
                @endif
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold;">Date</th>
            <th style="font-weight: bold;">Student Name</th>
            <th style="font-weight: bold;">Class</th>
            <th style="font-weight: bold;">Fee Type</th>
            <th style="font-weight: bold;">Amount Paid</th>
            <th style="font-weight: bold;">Discount</th>
            <th style="font-weight: bold;">Fine</th>
            <th style="font-weight: bold;">Payment Method</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['feeCollections'] as $collection)
        <tr>
            <td>{{ \Carbon\Carbon::parse($collection->payment_date)->format('Y-m-d') }}</td>
            <td>{{ optional($collection->student)->name }}</td>
            <td>{{ optional($collection->schoolClass)->name }} {{ optional($collection->classSection)->name }}</td>
            <td>{{ optional($collection->feeList->feeType)->name }}</td>
            <td>{{ $collection->amount_paid }}</td>
            <td>{{ $collection->discount }}</td>
            <td>{{ $collection->fine }}</td>
            <td>{{ optional($collection->paymentMethod)->name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="font-weight: bold;">Total:</td>
            <td style="font-weight: bold;">{{ $data['totalAmountCollected'] }}</td>
            <td style="font-weight: bold;">{{ $data['totalDiscount'] }}</td>
            <td style="font-weight: bold;">{{ $data['totalFine'] }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>