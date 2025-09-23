<div>
    <div class="row">
        <!-- Form Column -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">{{ $examId ? 'Edit' : 'Add' }} Exam</div>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label">Academic Session</label>
                            <select class="form-select" wire:model="academic_session_id">
                                <option value="">Select Session</option>
                                @foreach($academicSessions as $session)
                                <option value="{{ $session->id }}">{{ $session->name }}</option>
                                @endforeach
                            </select>
                            @error('academic_session_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Exam Category</label>
                            <select class="form-select" wire:model="exam_category_id">
                                <option value="">Select Category</option>
                                @foreach($examCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('exam_category_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" wire:model="start_at" />
                            @error('start_at') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" wire:model="end_at" />
                            @error('end_at') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Add Mark Distribution Checkboxes -->
                        <div class="mb-3">
                            <label class="form-label">Allowed Mark Distributions</label>
                            <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                @foreach($markDistributionTypes as $type)
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        value="{{ $type->id }}"
                                        id="dist_{{ $type->id }}"
                                        wire:model="selectedMarkDistributions">
                                    <label class="form-check-label" for="dist_{{ $type->id }}">
                                        {{ $type->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('selectedMarkDistributions') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <!-- End Mark Distribution Checkboxes -->

                        <button type="submit" class="btn btn-primary">{{ $examId ? 'Update' : 'Save' }}</button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-md-8">
            {{-- The entire table column remains exactly the same. No changes needed here. --}}
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">Exam List</div>
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
                            <input type="text" class="form-control form-control-sm" wire:model.live.debounce.300ms="search" placeholder="Search..." />
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th wire:click="sortBy('id')" style="cursor:pointer;">#</th>
                                    <th>Academic Session</th>
                                    <th>Category</th>
                                    <th wire:click="sortBy('start_at')" style="cursor:pointer;">Start Date</th>
                                    <th wire:click="sortBy('end_at')" style="cursor:pointer;">End Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exams as $exam)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $exam->academicSession->name }}</td>
                                    <td>{{ $exam->examCategory->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($exam->start_at)->format('d M, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($exam->end_at)->format('d M, Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $exam->id }})"><i class="ri-edit-line"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $exam->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="ri-delete-bin-line"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No exams found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $exams->links() }}
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
                    <h5 class="modal-title text-white">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this exam? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed" data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

</div>