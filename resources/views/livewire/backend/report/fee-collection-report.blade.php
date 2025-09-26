<div>
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title text-white m-0">Fee Collection Report</h3>
        </div>
        <div class="card-body">
            {{-- Filter Controls --}}
            <div class="row mb-3 align-items-end">
                <div class="col-md-3">
                    <label for="reportType">Report Type</label>
                    <select wire:model="reportType" wire:change="generateReport" class="form-control" id="reportType">
                        <option value="daily">Daily</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="custom">Custom Date Range</option>
                    </select>
                </div>

                @if ($reportType === 'daily')
                <div class="col-md-3"><label for="date">Date</label><input type="date" wire:model="date" wire:change="generateReport" class="form-control" id="date"></div>
                @elseif ($reportType === 'monthly')
                <div class="col-md-3"><label for="month">Month</label><input type="month" wire:model="month" wire:change="generateReport" class="form-control" id="month"></div>
                @elseif ($reportType === 'yearly')
                <div class="col-md-3"><label for="year">Year</label><input type="number" wire:model="year" wire:change="generateReport" class="form-control" id="year" placeholder="YYYY"></div>
                @elseif ($reportType === 'custom')
                <div class="col-md-3"><label for="fromDate">From</label><input type="date" wire:model="fromDate" wire:change="generateReport" class="form-control" id="fromDate"></div>
                <div class="col-md-3"><label for="toDate">To</label><input type="date" wire:model="toDate" wire:change="generateReport" class="form-control" id="toDate"></div>
                @endif
            </div>

            {{-- Summary Cards --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="info-box bg-primary p-3">
                        <div class="info-box-content text-white"><span class="info-box-text">Total Amount Collected </span><span class="info-box-number">{{ number_format($totalAmountCollected, 2) }}</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-danger p-3">
                        <div class="info-box-content text-white"><span class="info-box-text">Total Discount Given </span><span class="info-box-number">{{ number_format($totalDiscount, 2) }}</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-secondary p-3">
                        <div class="info-box-content text-white"><span class="info-box-text">Total Fine Collected </span><span class="info-box-number">{{ number_format($totalFine, 2) }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Fee Collection Details Table --}}
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Transaction Details</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Fee Type</th>
                                    <th class="text-right">Amount Paid</th>
                                    <th class="text-right">Discount</th>
                                    <th class="text-right">Fine</th>
                                    <th>Payment Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($feeCollections as $collection)
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
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No fee collections found for the selected period.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="4" class="text-right">Total:</td>
                                    <td class="text-right">{{ number_format($totalAmountCollected, 2) }}</td>
                                    <td class="text-right">{{ number_format($totalDiscount, 2) }}</td>
                                    <td class="text-right">{{ number_format($totalFine, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Download Buttons --}}
            <div class="mt-4">
                <button wire:click="export('pdf')" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Download PDF</button>
                <button wire:click="export('excel')" class="btn btn-secondary"><i class="fas fa-file-excel"></i> Download Excel</button>
            </div>
        </div>
    </div>
</div>