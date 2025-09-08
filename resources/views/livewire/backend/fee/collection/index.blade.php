<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">Fee Collection List</div>
                </div>
                <div class="card-body">

                    <!-- Top Controls -->
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex gap-2 align-items-center">
                            <select wire:model="perPage" class="form-select form-select-sm w-auto">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>Records per page</span>
                        </div>
                        <div class="w-25">
                            <input type="text" class="form-control form-control-sm" wire:model.debounce.500ms="search" placeholder="Search by student or fee list...">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th wire:click="sortBy('id')" style="cursor: pointer;">#
                                        @if($sortField === 'id')
                                        <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i>
                                        @endif
                                    </th>
                                    <th>Student</th>
                                    <th>Fee List</th>
                                    <th>Amount Paid</th>
                                    <th>Discount</th>
                                    <th>Fine</th>
                                    <th>Balance</th>
                                    <th>Payment Date</th>
                                    <th>Payment Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collections as $collection)
                                <tr>
                                    <td>{{ ($collections->currentPage() - 1) * $collections->perPage() + $loop->iteration }}</td>
                                    <td>{{ $collection->student->user->name ?? 'N/A' }}</td>
                                    <td>{{ $collection->feeList->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($collection->amount_paid, 2) }}</td>
                                    <td>{{ number_format($collection->discount, 2) }}</td>
                                    <td>{{ number_format($collection->fine, 2) }}</td>
                                    <td>{{ number_format($collection->balance, 2) }}</td>
                                    <td>{{ $collection->payment_date->format('d M, Y') }}</td>
                                    <td>{{ ucfirst($collection->paymentMethod->name ?? $collection->payment_method) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No fee collections found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $collections->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>