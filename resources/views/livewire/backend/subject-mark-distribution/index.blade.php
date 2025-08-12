<div class="card">


    <div class="card-header bg-primary">
        <div class="card-title m-0 text-white">Subject Mark Distributions</div>
    </div>

    <div class="card-body">

        <div class="border-bottom pb-3 mb-5 ">
            <h4 class="mb-3">Filter Subject Mark Distributions</h4>
            <div class="row">
                <div class="col-lg-2">
                    <!-- Class Filter -->
                    <select wire:change="changeClass($event.target.value)" class="form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected($filterClass==$class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <!-- Section Filter -->
                    <select wire:change="changeSection($event.target.value)" class="form-select" @disabled(empty($sections))>
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}" @selected($filterSection==$section->id)>{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <!-- Department Filter -->
                    <select wire:change="changeDepartment($event.target.value)" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" @selected($filterDepartment==$department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        wire:click="resetFilters"
                        @disabled(!$filterClass && !$filterSection && !$filterDepartment && !$search)>
                        Reset Filters
                    </button>

                </div>
            </div>
        </div>

        <div class="table-action d-flex justify-content-between mb-3 gap-2">

            <div class="d-flex gap-2 align-items-center">
                <select wire:change="changePerPage($event.target.value)" class="form-select form-select-sm w-auto">
                    <option value="5" @selected($perPage==5)>5</option>
                    <option value="10" @selected($perPage==10)>10</option>
                    <option value="25" @selected($perPage==25)>25</option>
                    <option value="50" @selected($perPage==50)>50</option>
                    <option value="100" @selected($perPage==100)>100</option>
                </select>
                <span>Records per page</span>
            </div>

            <div class="w-25">

                <!-- Search -->
                <input type="text" class="form-control form-control-sm" wire:model.debounce.500ms="search" placeholder="Search...">
            </div>
        </div>




        <table class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer">#</th>
                    <th wire:click="sortBy('school_class_id')" style="cursor: pointer">Class</th>
                    <th wire:click="sortBy('class_section_id')" style="cursor: pointer">Section</th>
                    <th wire:click="sortBy('subject_id')" style="cursor: pointer">Subject</th>
                    <th wire:click="sortBy('mark_distribution_id')" style="cursor: pointer">Mark Type</th>
                    <th wire:click="sortBy('mark')" style="cursor: pointer">Mark</th>
                    <th wire:click="sortBy('pass_mark')" style="cursor: pointer">Pass <Mark></Mark></th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($distributions as $dist)
                <tr>
                    <td>{{ $dist->id }}</td>
                    <td>{{ $dist->schoolClass->name ?? '-' }}</td>
                    <td>{{ $dist->classSection->name ?? '-' }}</td>
                    <td>{{ $dist->subject->name ?? '-' }}</td>
                    <td>{{ $dist->markDistribution->name ?? '-' }}</td>
                    <td>{{ $dist->mark }}</td>
                    <td>{{ $dist->pass_mark }}</td>
                    <td>
                        <a href="{{ route('admin.subject-mark-distribution.edit', $dist->id) }}" wire:navigate class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $dist->id }})">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <small>
            Showing {{ $distributions->firstItem() ?? 0 }} to {{ $distributions->lastItem() ?? 0 }} of {{ $distributions->total() }} entries
        </small>
        {{ $distributions->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade @if($confirmingDeleteId) show d-block @endif" tabindex="-1" style="@if($confirmingDeleteId) display:block; background:rgba(0,0,0,0.5); @endif">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" wire:click="$set('confirmingDeleteId', null)"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this entry? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDeleteId', null)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>