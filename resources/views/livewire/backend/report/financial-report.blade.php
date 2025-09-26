<div>
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title text-white m-0">Financial Ledger Report</h3>
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
                <div class="col-md-3"><label for="fromDate">From Date</label><input type="date" wire:model="fromDate" wire:change="generateReport" class="form-control" id="fromDate"></div>
                <div class="col-md-3"><label for="toDate">To Date</label><input type="date" wire:model="toDate" wire:change="generateReport" class="form-control" id="toDate"></div>
                @endif
            </div>

            {{-- Summary Cards --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="info-box bg-primary p-3">
                        <div class="info-box-content text-white"><span class="info-box-text">Total Income </span><span class="info-box-number">{{ number_format($totalIncome, 2) }}</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-danger p-3">
                        <div class="info-box-content text-white"><span class="info-box-text">Total Expense </span><span class="info-box-number">{{ number_format($totalExpense, 2) }}</span></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-info p-3">
                        <div class="info-box-content text-white"><span class="info-box-text">Net Balance </span><span class="info-box-number">{{ number_format($netBalance, 2) }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Ledger Details Table --}}
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Ledger Details</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th class="text-right">Income (Credit)</th>
                                    <th class="text-right">Expense (Debit)</th>
                                    <th class="text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ledgerDetails as $entry)
                                <tr>
                                    <td>{{ $entry['date'] }}</td>
                                    <td>{{ $entry['description'] }}</td>
                                    <td class="text-right text-success">{{ $entry['income'] > 0 ? number_format($entry['income'], 2) : '-' }}</td>
                                    <td class="text-right text-danger">{{ $entry['expense'] > 0 ? number_format($entry['expense'], 2) : '-' }}</td>
                                    <td class="text-right font-weight-bold">{{ number_format($entry['balance'], 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No transactions found for the selected period.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="2" class="text-right">Total:</td>
                                    <td class="text-right">{{ number_format($totalIncome, 2) }}</td>
                                    <td class="text-right">{{ number_format($totalExpense, 2) }}</td>
                                    <td class="text-right">{{ number_format($netBalance, 2) }}</td>
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