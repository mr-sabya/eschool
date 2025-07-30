<div>
    <div class="row">
        <!-- Form Column -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">{{ $gradeId ? 'Edit' : 'Add' }} Grade</div>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label>Grade Name</label>
                            <input type="text" class="form-control" wire:model="grade_name">
                            @error('grade_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Grade Point</label>
                            <input type="number" step="0.01" class="form-control" wire:model="grade_point">
                            @error('grade_point') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Start Marks</label>
                            <input type="number" class="form-control" wire:model="start_marks">
                            @error('start_marks') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>End Marks</label>
                            <input type="number" class="form-control" wire:model="end_marks">
                            @error('end_marks') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Remarks</label>
                            <input type="text" class="form-control" wire:model="remarks">
                        </div>

                        <button type="submit" class="btn btn-primary">{{ $gradeId ? 'Update' : 'Save' }}</button>
                        <button type="button" class="btn btn-secondary" wire:click="resetForm">Reset</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">Grade List</div>
                </div>
                <div class="card-body">
                    <div class="table-action d-flex justify-content-between mb-3">
                        <div class="d-flex gap-2 align-items-center">
                            <select wire:model="perPage" class="form-select form-select-sm w-auto">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span>Records per page</span>
                        </div>
                        <div class="w-25">
                            <input type="text" class="form-control form-control-sm" wire:model.debounce.500ms="search" placeholder="Search...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th wire:click="sortBy('id')" style="cursor: pointer;"># @if($sortField === 'id') <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-fill"></i> @endif</th>
                                    <th wire:click="sortBy('grade_name')" style="cursor: pointer;">Grade @if($sortField === 'grade_name') <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-fill"></i> @endif</th>
                                    <th>Point</th>
                                    <th>Range</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grades as $grade)
                                <tr>
                                    <td>{{ ($grades->currentPage() - 1) * $grades->perPage() + $loop->iteration }}</td>
                                    <td>{{ $grade->grade_name }}</td>
                                    <td>{{ number_format($grade->grade_point, 2) }}</td>
                                    <td>{{ $grade->start_marks }} - {{ $grade->end_marks }}</td>
                                    <td>{{ $grade->remarks }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $grade->id }})"><i class="ri-edit-line"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $grade->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="ri-delete-bin-line"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No grades found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $grades->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this grade?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" wire:click="deleteConfirmed" data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>