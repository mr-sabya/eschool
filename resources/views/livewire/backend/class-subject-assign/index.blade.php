<div>
    <div class="card">
        <div class="card-header bg-primary">
            <div class="card-title m-0 text-white">Subject Assignments List</div>
        </div>

        <!-- Filter & Copy Section -->
        <div class="card-body border-bottom bg-light">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fw-bold">From Session</label>
                    <select wire:model.live="selectedSessionId" class="form-select">
                        <option value="">All Sessions</option>
                        @foreach($sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Class</label>
                    <select wire:model.live="selectedClassId" class="form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Section</label>
                    <select wire:model.live="selectedSectionId" class="form-select" {{ empty($selectedClassId) ? 'disabled' : '' }}>
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Department</label>
                    <select wire:model.live="selectedDepartmentId" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">Reset Filters</button>
                </div>
            </div>

            <hr>

            <!-- ✅ COPY SECTION -->
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="text-primary fw-bold"><i class="ri-file-copy-line"></i> Bulk Copy Assignments</div>
                    <small class="text-muted">Copy filtered data from <b>{{ $selectedSessionId ? 'Selected Session' : 'All Sessions' }}</b> to:</small>
                </div>
                <div class="col-md-3">
                    <select wire:model="copyToSessionId" class="form-select border-primary">
                        <option value="">-- Target Session --</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button wire:click="copyAssignments" class="btn btn-primary w-100" 
                        onclick="confirm('Are you sure you want to copy these assignments to the selected session?') || event.stopImmediatePropagation()"
                        @if(!$copyToSessionId || !$selectedSessionId) disabled @endif>
                        Copy All to Next Session
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-action d-flex justify-content-between mb-3">
                <div class="d-flex gap-2 align-items-center">
                    <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span>Records per page</span>
                </div>
                <div class="w-25">
                    <input type="text" class="form-control form-control-sm" wire:model.live.debounce.500ms="search" placeholder="Search class or subject...">
                </div>
            </div>

            <table class="table table-bordered table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th wire:click="sortBy('id')" style="cursor: pointer;"># <i class="ri-arrow-up-down-line small text-muted"></i></th>
                        <th>Session</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Department</th>
                        <th>Subject</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjectAssigns as $assign)
                    <tr>
                        <td>{{ $assign->id }}</td>
                        <td>{{ $assign->academicSession->name ?? '-' }}</td>
                        <td>{{ $assign->schoolClass->name ?? '-' }}</td>
                        <td>{{ $assign->classSection->name ?? '-' }}</td>
                        <td>{{ $assign->department->name ?? 'General' }}</td>
                        <td>{{ $assign->subject->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.class-subject-assign.edit', $assign->id) }}" class="btn btn-sm btn-primary"><i class="ri-edit-line"></i></a>
                            <button wire:click="confirmDelete({{ $assign->id }})" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No subject assignments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <small>Showing {{ $subjectAssigns->firstItem() ?? 0 }} to {{ $subjectAssigns->lastItem() ?? 0 }} of {{ $subjectAssigns->total() }} assignments</small>
            {{ $subjectAssigns->links() }}
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade @if($confirmingDelete) show d-block @endif" tabindex="-1" style="@if($confirmingDelete) display:block; background:rgba(0,0,0,0.5); @endif">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" wire:click="$set('confirmingDelete', false)"></button></div>
                <div class="modal-body">Delete this assignment? This cannot be undone.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>