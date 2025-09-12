<div>
    <button type="button" class="btn btn-primary mb-3"
        data-bs-toggle="modal" data-bs-target="#incomeModal"
        wire:click="create">
        Add Income
    </button>
    <div class="card">
        <div class="card-header bg-primary">
            <div class="card-title m-0 text-white">Income Management</div>
        </div>
        <div class="card-body">
            <!-- Top Actions -->
            <div class="d-flex justify-content-between mb-3">
                <div class="d-flex gap-2 align-items-center">


                    <div class="d-flex gap-2 align-items-center">
                        <select wire:model="perPage" class="form-select form-select-sm w-auto">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="d-none d-sm-inline">Records per page</span>
                    </div>
                </div>

                <div class="w-25">
                    <input type="text" wire:model.debounce.500ms="search"
                        class="form-control" placeholder="Search...">
                </div>
            </div>

            <!-- Income Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('id')" style="cursor:pointer">#
                                @if($sortField==='id')<i class="{{ $sortDirection==='asc'?'ri-arrow-up-s-fill':'ri-arrow-down-s-fill' }}"></i>@endif
                            </th>
                            <th>Invoice #</th>
                            <th>Head</th>
                            <th wire:click="sortBy('amount')" style="cursor:pointer">Amount
                                @if($sortField==='amount')<i class="{{ $sortDirection==='asc'?'ri-arrow-up-s-fill':'ri-arrow-down-s-fill' }}"></i>@endif
                            </th>
                            <th wire:click="sortBy('date')" style="cursor:pointer">Date
                                @if($sortField==='date')<i class="{{ $sortDirection==='asc'?'ri-arrow-up-s-fill':'ri-arrow-down-s-fill' }}"></i>@endif
                            </th>
                            <th>Payment Method</th>
                            <th>Note</th>
                            <th>Attachment</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incomes as $income)
                        <tr>
                            <td>{{ ($incomes->currentPage()-1)*$incomes->perPage() + $loop->iteration }}</td>
                            <td>{{ $income->invoice_number ?? '-' }}</td>
                            <td>{{ $income->head->name ?? '-' }}</td>
                            <td>{{ $income->amount }}</td>
                            <td>{{ $income->date }}</td>
                            <td>{{ $income->paymentMethod->name ?? '-' }}</td>
                            <td>{{ $income->note }}</td>
                            <td>
                                @if($income->attachment)
                                <a href="{{ asset('storage/'.$income->attachment) }}" target="_blank" class="btn btn-sm btn-info">View</a>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $income->user->name ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal" data-bs-target="#incomeModal"
                                    wire:click="edit({{ $income->id }})">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="btn btn-sm btn-danger"
                                    wire:click="confirmDelete({{ $income->id }})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No income records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $incomes->links() }}
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div wire:ignore.self class="modal fade" id="incomeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">{{ $incomeId ? 'Edit Income' : 'Add Income' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Invoice Number</label>
                                    <input type="text" wire:model="invoice_number" class="form-control">
                                    @error('invoice_number') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Income Head</label>
                                    <select wire:model="income_head_id" class="form-select">
                                        <option value="">-- Select --</option>
                                        @foreach($heads as $head)
                                        <option value="{{ $head->id }}">{{ $head->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('income_head_id') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Amount</label>
                                    <input type="number" wire:model="amount" class="form-control">
                                    @error('amount') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Date</label>
                                    <input type="date" wire:model="date" class="form-control">
                                    @error('date') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Payment Method</label>
                                    <select wire:model="payment_method_id" class="form-select">
                                        <option value="">-- Select --</option>
                                        @foreach($paymentMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_method_id') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label>Attachment</label>
                                    <input type="file" wire:model="attachment" class="form-control">
                                    @error('attachment') <span class="text-danger">{{ $message }}</span>@enderror

                                    @if($attachment)
                                    <p class="mt-2">Preview: <strong>{{ $attachment->getClientOriginalName() }}</strong></p>
                                    @elseif($existing_attachment)
                                    <p class="mt-2">Existing: <a href="{{ asset('storage/'.$existing_attachment) }}" target="_blank">View File</a></p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label>Note</label>
                                    <textarea wire:model="note" class="form-control"></textarea>
                                    @error('note') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">{{ $incomeId ? 'Update' : 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this income record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('close-modal', () => {
            let modal = bootstrap.Modal.getInstance(document.getElementById('incomeModal'));
            if (modal) modal.hide();
        });

        Livewire.on('open-delete-modal', () => {
            let modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });

        Livewire.on('close-delete-modal', () => {
            let modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            if (modal) modal.hide();
        });

    });
</script>