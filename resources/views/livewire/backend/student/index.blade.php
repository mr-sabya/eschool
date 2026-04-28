<div class="card shadow-sm">
    <div class="card-header bg-primary py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 text-white"><i class="fas fa-users me-2"></i>Students Management</h5>
        <a href="{{ route('admin.student.create') }}" class="btn btn-sm btn-light text-primary fw-bold" wire:navigate>
            <i class="fas fa-plus-circle me-1"></i> Add New Student
        </a>
    </div>

    <div class="card-body">
        <!-- 1. Filters Header -->
        <div class="row g-2 mb-4 border-bottom pb-4">
            <div class="col-md-2">
                <label class="form-label small fw-bold">Academic Session</label>
                <select wire:model.live="filter_session_id" class="form-select form-select-sm border-info">
                    <option value="">All Sessions</option>
                    @foreach($sessions as $session)
                    <option value="{{ $session->id }}">{{ $session->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold">Class</label>
                <select wire:model.live="filter_class_id" class="form-select form-select-sm border-primary">
                    <option value="">All Classes</option>
                    @foreach($allClasses as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold">Section</label>
                <select wire:model.live="filter_section_id" class="form-select form-select-sm border-primary" {{ $filter_class_id ? '' : 'disabled' }}>
                    <option value="">All Sections</option>
                    @foreach($this->filteredSections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-bold">Department</label>
                <select wire:model.live="filter_department_id" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 align-self-end">
                <button class="btn btn-sm btn-outline-secondary w-100" wire:click="resetFilters">
                    <i class="fas fa-sync-alt me-1"></i> Reset Filters
                </button>
            </div>
        </div>

        <!-- 2. Bulk Action Tool -->
        <div class="row mb-4 bg-light p-3 rounded border mx-0 align-items-center shadow-sm">
            <div class="col-md-4 border-end">
                <span class="text-dark fw-bold d-block"><i class="fas fa-tools me-2 text-warning"></i>Bulk Session Update</span>
                <small class="text-muted">Change session for all currently filtered students</small>
            </div>
            <div class="col-md-4">
                <select wire:model="new_session_id" class="form-select form-select-sm border-warning">
                    <option value="">-- Choose Target Session --</option>
                    @foreach($sessions as $session)
                    <option value="{{ $session->id }}">{{ $session->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-sm btn-warning w-100 fw-bold"
                    wire:click="$set('confirmingBulkSessionUpdate', true)"
                    @if(!$new_session_id) disabled @endif>
                    Update Session for Filtered Results
                </button>
            </div>
        </div>

        <!-- 3. Table Toolbar -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center gap-2">
                <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="small text-muted">Entries</span>
            </div>

            <div class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Name, Roll, Phone...">
                </div>

                <div class="btn-group btn-group-sm">
                    <button wire:click="export" class="btn btn-outline-success">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </button>
                    <button wire:click="exportSeatPlan" class="btn btn-outline-info">
                        <i class="fas fa-id-card me-1"></i> Seat Plan
                    </button>
                </div>
            </div>
        </div>

        <!-- 4. Main Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th wire:click="sortBy('id')" style="cursor:pointer; width: 80px;">
                            ID @if($sortField === 'id') <i class="fas fa-sort-{{$sortDirection === 'asc' ? 'up' : 'down'}} float-end"></i> @endif
                        </th>
                        <th wire:click="sortBy('name')" style="cursor:pointer;">
                            Student Name @if($sortField === 'name') <i class="fas fa-sort-{{$sortDirection === 'asc' ? 'up' : 'down'}} float-end"></i> @endif
                        </th>
                        <th>Class & Section</th>
                        <th>Session</th>
                        <th class="text-center">Profile</th>
                        <th class="text-center" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $user)
                    <tr wire:key="student-{{ $user->id }}">
                        <td class="fw-bold">{{ $user->id }}</td>
                        <td>
                            <div class="fw-bold text-primary">{{ $user->name }}</div>
                            <small class="text-muted">Roll: {{ $user->student->roll_number ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $user->student->schoolClass->name ?? 'N/A' }}
                                ({{ $user->student->classSection->name ?? '-' }})
                            </span>
                        </td>
                        <td>
                            <span class="badge outline-secondary text-dark border">
                                {{ $user->student->academicSession->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($user->student->profile_picture)
                            <img src="{{ asset('storage/'.$user->student->profile_picture) }}" width="35" height="35" class="rounded-circle shadow-sm border">
                            @else
                            <div class="rounded-circle bg-light d-inline-block border" style="width:35px; height:35px; line-height:35px;">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.student.edit', $user->student->id) }}" class="btn btn-outline-primary" title="Edit" wire:navigate>
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button wire:click="confirmDelete({{ $user->id }})" class="btn btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-3x mb-3 d-block"></i>
                            No students found matching your criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
            </div>
            {{ $students->links() }}
        </div>
    </div>

    <!-- Modal: Delete Confirmation -->
    @if($confirmingDelete)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('confirmingDelete', false)"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="fs-5">Are you sure you want to delete this student record? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Yes, Delete Student</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal: Bulk Update Confirmation -->
    @if($confirmingBulkSessionUpdate)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-warning">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-circle me-2"></i>Bulk Session Update</h5>
                    <button type="button" class="btn-close" wire:click="$set('confirmingBulkSessionUpdate', false)"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to update <strong>ALL</strong> filtered students to a new session.</p>
                    <div class="alert alert-light border">
                        <ul class="mb-0 small text-muted">
                            <li>Target Session: <span class="text-dark fw-bold">{{ $sessions->firstWhere('id', $new_session_id)->name ?? 'Unknown' }}</span></li>
                            <li>Applied Filters:
                                <span class="badge bg-secondary">{{ $filter_class_id ? 'Class Selected' : 'All Classes' }}</span>
                                <span class="badge bg-secondary">{{ $search ? 'Search Active' : '' }}</span>
                            </li>
                        </ul>
                    </div>
                    <p class="text-danger small fw-bold mt-2">Note: This will modify records for every student currently visible in your filtered list.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingBulkSessionUpdate', false)">Cancel</button>
                    <button type="button" class="btn btn-warning fw-bold" wire:click="updateFilteredSessions">
                        <span wire:loading wire:target="updateFilteredSessions" class="spinner-border spinner-border-sm me-1"></span>
                        Confirm Bulk Update
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>