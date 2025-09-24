<div>
    <div class="card">
        <div class="card-header bg-primary">
            <div class="card-title m-0 text-white">Subject Assignments List</div>
        </div>

        <!-- START: NEW FILTER SECTION -->
        <div class="card-body border-bottom">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Session</label>
                    <select wire:model="selectedSessionId" class="form-select">
                        <option value="">All Sessions</option>
                        @foreach($sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Class</label>
                    <select wire:model="selectedClassId" class="form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- These dropdowns will only show up if a class is selected --}}
                @if(!empty($selectedClassId))
                <div class="col-md-2">
                    <label class="form-label">Section</label>
                    <select wire:model="selectedSectionId" class="form-select">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Department</label>
                    <select wire:model="selectedDepartmentId" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-secondary w-100">Reset</button>
                </div>
            </div>
        </div>
        <!-- END: NEW FILTER SECTION -->

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
                    <input type="text" class="form-control form-control-sm" wire:model.debounce.500ms="search" placeholder="Search by class, subject, etc...">
                </div>
            </div>

            <table class="table table-bordered table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th wire:click="sortBy('id')" style="cursor: pointer;">
                            #
                            @if($sortField === 'id')
                            <i class="{{ $sortAsc ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }} ms-1"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('academic_session_id')" style="cursor: pointer;">
                            Session
                            @if($sortField === 'academic_session_id')
                            <i class="{{ $sortAsc ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }} ms-1"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('school_class_id')" style="cursor: pointer;">
                            Class
                            @if($sortField === 'school_class_id')
                            <i class="{{ $sortAsc ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }} ms-1"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('class_section_id')" style="cursor: pointer;">
                            Section
                            @if($sortField === 'class_section_id')
                            <i class="{{ $sortAsc ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }} ms-1"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('department_id')" style="cursor: pointer;">
                            Department
                            @if($sortField === 'department_id')
                            <i class="{{ $sortAsc ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }} ms-1"></i>
                            @endif
                        </th>
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
                        <td>{{ $assign->department->name ?? 'General' }}</td> {{-- Display General if null --}}
                        <td>{{ $assign->subject->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.class-subject-assign.edit', $assign->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <button wire:click="confirmDelete({{ $assign->id }})" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No subject assignments found.</td> {{-- Colspan updated to 7 --}}
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <small>Showing {{ $subjectAssigns->firstItem() ?? 0 }} to {{ $subjectAssigns->lastItem() ?? 0 }} of {{ $subjectAssigns->total() }} assignments</small>
            {{ $subjectAssigns->links() }}
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade @if($confirmingDelete) show d-block @endif" tabindex="-1"
            style="@if($confirmingDelete) display:block; background:rgba(0,0,0,0.5); @endif">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" wire:click="$set('confirmingDelete', false)"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this assignment? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>