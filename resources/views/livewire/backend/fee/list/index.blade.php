<div>
    <div class="row">
        <!-- Form Column -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">{{ $feeListId ? 'Edit' : 'Add' }} Fee List</div>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label>Academic Session</label>
                            <select wire:model="academic_session_id" class="form-select">
                                <option value="">-- Select Session --</option>
                                @foreach($academicSessions as $session)
                                <option value="{{ $session->id }}">{{ $session->name }}</option>
                                @endforeach
                            </select>
                            @error('academic_session_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Fee Type</label>
                            <select wire:model="fee_type_id" class="form-select">
                                <option value="">-- Select Fee Type --</option>
                                @foreach($feeTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('fee_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Class</label>
                            <select wire:model="school_class_id" wire:change="onClassChange($event.target.value)" class="form-select">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('school_class_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Section</label>
                            <select wire:model="class_section_id" class="form-select" @if($sections->isEmpty()) disabled @endif>
                                <option value="">-- Select Section --</option>
                                @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            @error('class_section_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" wire:model="name" class="form-control">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Amount</label>
                            <input type="number" step="0.01" wire:model="amount" class="form-control">
                            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Due Date</label>
                            <input type="date" wire:model="due_date" class="form-control">
                            @error('due_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" wire:model="is_active" id="isActive">
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>

                        <button class="btn btn-primary" type="submit">{{ $feeListId ? 'Update' : 'Save' }}</button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">Fee List</div>
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
                            <input type="text" class="form-control form-control-sm" wire:model.debounce.500ms="search" placeholder="Search...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th wire:click="sortBy('id')" style="cursor: pointer;">
                                        # @if($sortField === 'id') <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                                    </th>
                                    <th wire:click="sortBy('name')" style="cursor: pointer;">
                                        Name @if($sortField === 'name') <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                                    </th>
                                    <th>Session</th>
                                    <th>Fee Type</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($feeLists as $list)
                                <tr>
                                    <td>{{ ($feeLists->currentPage() - 1) * $feeLists->perPage() + $loop->iteration }}</td>
                                    <td>{{ $list->name }}</td>
                                    <td>{{ $list->academicSession->name ?? '-' }}</td>
                                    <td>{{ $list->feeType->name ?? '-' }}</td>
                                    <td>{{ $list->schoolClass->name ?? '-' }}</td>
                                    <td>{{ $list->classSection->name ?? '-' }}</td>
                                    <td>{{ number_format($list->amount, 2) }}</td>
                                    <td>{{ $list->due_date }}</td>
                                    <td>
                                        <span class="badge {{ $list->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $list->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $list->id }})"><i class="ri-edit-line"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $list->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="ri-delete-bin-line"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No fee lists found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $feeLists->links() }}
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this fee list? This action cannot be undone.
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