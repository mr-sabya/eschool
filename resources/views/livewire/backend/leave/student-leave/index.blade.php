<div>
    <div class="row">
        <!-- Form Column -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">{{ $leaveId ? 'Edit' : 'Add' }} Student Leave</div>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label>Class</label>
                            <select wire:model="school_class_id" wire:change="loadSections($event.target.value)" class="form-control">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Section</label>
                            <select wire:model="class_section_id" wire:change="loadStudents($event.target.value)"
                                wire:key="section-select-{{ $class_section_id }}"
                                class="form-control" @if(count($sections)===0) disabled @endif>
                                <option value="">-- Select Section --</option>
                                @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Student</label>
                            <select wire:model="student_id"
                                wire:key="student-select-{{ $student_id }}"
                                class="form-control" @if(count($students)===0) disabled @endif>
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->roll_number }} - {{ $student->user->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-3">
                            <label>Leave Type</label>
                            <select class="form-select" wire:model="leave_type_id">
                                <option value="">-- None --</option>
                                @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('leave_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Start Date</label>
                            <input type="date" class="form-control" wire:model="start_date">
                            @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>End Date</label>
                            <input type="date" class="form-control" wire:model="end_date">
                            @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Reason</label>
                            <textarea class="form-control" wire:model="reason"></textarea>
                            @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Attachment (optional)</label>
                            <input type="file" wire:model="attachment" class="form-control">
                            @if ($attachment)
                            <p class="mt-2 text-success">Preview: {{ $attachment->getClientOriginalName() }}</p>
                            @elseif($leaveId && $leaves->where('id',$leaveId)->first()?->attachment)
                            <a href="{{ Storage::url($leaves->where('id',$leaveId)->first()->attachment) }}" target="_blank">View current file</a>
                            @endif
                            @error('attachment') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" wire:model="status">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button class="btn btn-primary" type="submit">{{ $leaveId ? 'Update' : 'Save' }}</button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">Student Leave List</div>
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
                                    <th>Roll Number</th>
                                    <th>Student</th>
                                    <th>Type</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Status</th>
                                    <th>Attachment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($leaves as $leave)
                                <tr>
                                    <td>{{ ($leaves->currentPage() - 1) * $leaves->perPage() + $loop->iteration }}</td>
                                    <td>{{ $leave->student->roll_number }}</td>
                                    <td>{{ $leave->student->user['name'] ?? '-' }}</td>
                                    <td>{{ $leave->leaveType->name ?? '-' }}</td>
                                    <td>{{ $leave->start_date }}</td>
                                    <td>{{ $leave->end_date }}</td>
                                    <td>
                                        <span class="badge bg-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($leave->attachment)
                                        @php
                                        $ext = pathinfo($leave->attachment, PATHINFO_EXTENSION);
                                        @endphp

                                        @if(in_array($ext, ['jpg','jpeg','png','gif']))
                                        <img src="{{ asset('storage/' . $leave->attachment) }}" alt="Attachment" style="max-width: 80px; max-height: 80px;">
                                        @else
                                        <a href="{{ asset('storage/' . $leave->attachment) }}" target="_blank">View File</a>
                                        @endif
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $leave->id }})"><i class="ri-edit-line"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $leave->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="ri-delete-bin-line"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No leaves found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $leaves->links() }}
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
                    Are you sure you want to delete this leave? This action cannot be undone.
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