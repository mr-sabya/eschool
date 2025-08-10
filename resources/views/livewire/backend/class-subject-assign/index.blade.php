<div>
    <div class="card">
        <div class="card-header bg-primary">
            <div class="card-title m-0 text-white">Subject Assignments List</div>
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

            <table class="table table-bordered table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th wire:click="sortBy('id')" style="cursor: pointer;">
                            #
                            @if($sortField === 'id')
                            <i class="{{ $sortAsc === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }} ms-1"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('academic_session_id')" style="cursor: pointer;">
                            Session
                            @if($sortField === 'academic_session_id')
                            <i class="{{ $sortAsc === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }} ms-1"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('school_class_id')" style="cursor: pointer;">
                            Class
                            @if($sortField === 'school_class_id')
                            <i class="{{ $sortAsc === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }} ms-1"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('class_section_id')" style="cursor: pointer;">
                            Section
                            @if($sortField === 'class_section_id')
                            <i class="{{ $sortAsc === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }} ms-1"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('department_id')" style="cursor: pointer;">
                            Department
                            @if($sortField === 'department_id')
                            <i class="{{ $sortAsc === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }} ms-1"></i>
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
                        <td>{{ $assign->department->name ?? '-' }}</td>
                        <td>{{ $assign->subject->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.class-subject-assign.edit', $assign->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <button wire:click="confirmDelete({{ $assign->id }})" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No subject assignments found.</td>
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