<div class="card">
    <div class="card-header bg-primary">
        <div class="card-title m-0 text-white">Students List</div>
    </div>

    <div class="card-body">

        <div class="row mb-3 border-bottom pb-3">
            <div class="col-md-3">
                <select wire:model="filter_class_id" class="form-select" wire:change="getFilteredSectionsProperty">
                    <option value="">All Classes</option>
                    @foreach($allClasses as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select wire:model="filter_section_id" class="form-select" {{ $filter_class_id ? '' : 'disabled' }}>
                    <option value="">All Sections</option>
                    @foreach($this->filteredSections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select wire:model="filter_department_id" class="form-select" {{ $filter_department_id === null ? '' : '' }}>
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3">
                <button class="btn btn-secondary" wire:click="$set('filter_class_id', null); $set('filter_section_id', null);">Reset Filters</button>

            </div>
        </div>

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

        <table class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer">#</th>
                    <th wire:click="sortBy('name')" style="cursor: pointer">Name</th>
                    <th>Class</th>
                    <th>Profile</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->student->schoolClass->name ?? '-' }}</td>
                    <td>
                        @if($user->student->profile_picture)
                        <img src="{{ asset('storage/'.$user->student->profile_picture) }}" width="40" class="rounded">
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary"
                            wire:click="$emit('edit', {{ $user->student->id }})">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger"
                            wire:click="confirmDelete({{ $user->id }})">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No students found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <small>Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students</small>
        {{ $students->links() }}
    </div>

    <!-- ✅ Delete Confirmation Modal -->
    <div class="modal fade @if($confirmingDelete) show d-block @endif" tabindex="-1" style="@if($confirmingDelete) display:block; background:rgba(0,0,0,0.5); @endif">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" wire:click="$set('confirmingDelete', false)"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this student? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>