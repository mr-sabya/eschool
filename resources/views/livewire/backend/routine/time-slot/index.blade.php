<div>
    <div class="row">
        <!-- Form Column -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="card-title m-0 text-white">{{ $timeSlotId ? 'Edit' : 'Add New' }} Time Slot</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name (Optional)</label>
                            <input type="text" id="name" class="form-control" wire:model="name" placeholder="e.g., Period 1, Lunch">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="time" id="start_time" class="form-control" wire:model="start_time">
                            @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_time" class="form-label">End Time</label>
                            <input type="time" id="end_time" class="form-control" wire:model="end_time">
                            @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button class="btn btn-primary" type="submit">{{ $timeSlotId ? 'Update' : 'Save' }}</button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="card-title m-0 text-white">List of Time Slots</h5>
                </div>
                <div class="card-body">
                    <div class="table-action d-flex justify-content-between mb-3">
                        <div class="d-flex gap-2 align-items-center">
                            <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>Records per page</span>
                        </div>

                        <div class="w-25">
                            <input type="text" class="form-control form-control-sm" wire:model.live.debounce.300ms="search" placeholder="Search by name...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th wire:click="sortBy('id')" style="cursor: pointer;">
                                        # @if($sortField === 'id') <i class="ri-{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}-s-fill"></i> @endif
                                    </th>
                                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                                        Name @if($sortField === 'name') <i class="ri-{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}-s-fill"></i> @endif
                                    </th>
                                    <th wire:click="sortBy('start_time')" style="cursor: pointer;">
                                        Start Time @if($sortField === 'start_time') <i class="ri-{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}-s-fill"></i> @endif
                                    </th>
                                    <th wire:click="sortBy('end_time')" style="cursor: pointer;">
                                        End Time @if($sortField === 'end_time') <i class="ri-{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}-s-fill"></i> @endif
                                    </th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($timeSlots as $slot)
                                <tr wire:key="{{ $slot->id }}">
                                    <td>{{ ($timeSlots->currentPage() - 1) * $timeSlots->perPage() + $loop->iteration }}</td>
                                    <td>{{ $slot->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $slot->id }})"><i class="ri-edit-line"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $slot->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="ri-delete-bin-line"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No time slots found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $timeSlots->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this time slot? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed" data-bs-dismiss="modal">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>