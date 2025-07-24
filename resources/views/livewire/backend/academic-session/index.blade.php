<div>
    <div class="row">
        <!-- Form Column -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">{{ $academicSessionId ? 'Edit' : 'Add' }} Academic Session</div>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control" wire:model="name" />
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Start Date</label>
                            <input type="date" class="form-control" wire:model="start_date" />
                            @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>End Date</label>
                            <input type="date" class="form-control" wire:model="end_date" />
                            @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" wire:model="is_active" id="isActive" />
                            <label class="form-check-label" for="isActive">Is Active</label>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ $academicSessionId ? 'Update' : 'Save' }}</button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">Academic Session List</div>
                </div>
                <div class="card-body">
                    <div class="table-action d-flex justify-content-between mb-3">
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
                            <input type="text" class="form-control form-control-sm" wire:model.debounce.500ms="search" placeholder="Search..." />
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th wire:click="sortBy('id')" style="cursor:pointer;">
                                        # @if($sortField === 'id') <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                                    </th>
                                    <th wire:click="sortBy('name')" style="cursor:pointer;">
                                        Name @if($sortField === 'name') <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                                    </th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th wire:click="sortBy('is_active')" style="cursor:pointer;">
                                        Status @if($sortField === 'is_active') <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                                    </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                <tr>
                                    <td>{{ ($sessions->currentPage() - 1) * $sessions->perPage() + $loop->iteration }}</td>
                                    <td>{{ $session->name }}</td>
                                    <td>{{ $session->start_date ?? '-' }}</td>
                                    <td>{{ $session->end_date ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $session->is_active ? 'primary' : 'secondary' }}">
                                            {{ $session->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $session->id }})"><i class="ri-edit-line"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $session->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="ri-delete-bin-line"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No sessions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $sessions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this session? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed" data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>