<div class="card">
    <div class="card-header bg-primary">
        <div class="card-title m-0 text-white">Final Mark Configurations</div>
    </div>

    <div class="card-body">
        <div class="border-bottom pb-3 mb-4">
            <h4 class="mb-3">Filter Final Mark Configurations</h4>
            <div class="row">
                <div class="col-lg-2">
                    <select wire:change="changeClass($event.target.value)" class="form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected($filterClass==$class->id)>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <select wire:change="changeDepartment($event.target.value)" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" @selected($filterDepartment==$department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <select wire:change="changeExam($event.target.value)" class="form-select">
                        <option value="">All Exams</option>
                        @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" @selected($filterExam==$exam->id)>{{ $exam->examCategory->name ?? 'Exam' }} ({{ \Carbon\Carbon::parse($exam->start_at)->format('d M, Y') }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-secondary" wire:click="resetFilters">
                        Reset Filters
                    </button>
                    {{-- Add Duplicate Button --}}
                    @if($showResults && $configs->total() > 0)
                    <button type="button" class="btn btn-info" wire:click="duplicate" wire:navigate>
                        <i class="fas fa-copy me-1"></i> Duplicate These Results
                    </button>
                    @endif
                </div>
            </div>
        </div>

        @if($showResults)
        <div class="table-action d-flex justify-content-between mb-3">
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
                <input type="text" class="form-control form-control-sm" wire:model.live="search" placeholder="Search by Class or Subject...">
            </div>
        </div>

        <table class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer">#</th>
                    <th wire:click="sortBy('exam_id')" style="cursor: pointer">Exam</th>
                    <th wire:click="sortBy('school_class_id')" style="cursor: pointer">Class</th>
                    <th wire:click="sortBy('department_id')" style="cursor: pointer">Department</th>
                    <th wire:click="sortBy('subject_id')" style="cursor: pointer">Subject</th>
                    <th wire:click="sortBy('class_test_total')" style="cursor: pointer">Class Test Total</th>
                    <th wire:click="sortBy('other_parts_total')" style="cursor: pointer">Other Parts Total</th>
                    <th wire:click="sortBy('final_result_weight_percentage')" style="cursor: pointer">Final Result Weight (%)</th>
                    <th wire:click="sortBy('grading_scale')" style="cursor: pointer">Grading Scale</th>
                    <th class="text-center" wire:click="sortBy('exclude_from_gpa')" style="cursor: pointer">Exclude from GPA</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($configs as $config)
                <tr>
                    <td>{{ $config->id }}</td>
                    <td>
                        {{ $config->exam->examCategory->name ?? 'Exam' }} ({{ \Carbon\Carbon::parse($config->exam->start_at)->format('d M, Y') }})
                    </td>
                    <td>{{ $config->schoolClass->name ?? '-' }}</td>
                    <td>{{ $config->department->name ?? '-' }}</td>
                    <td>{{ $config->subject->name ?? '-' }}</td>
                    <td>{{ $config->class_test_total }}</td>
                    <td>{{ $config->other_parts_total }}</td>
                    <td>{{ $config->final_result_weight_percentage }}</td>
                    <td>{{ $config->grading_scale == 100 ? '100 Marks' : '50 Marks' }}</td>
                    <td class="text-center">
                        {{ $config->exclude_from_gpa ? 'Yes' : 'No' }}
                    </td>
                    <td>
                        <a href="{{ route('admin.final-mark-configuration.edit', $config->id) }}" wire:navigate class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $config->id }})">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center text-muted">No records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="card-footer d-flex justify-content-between">
            <small>
                Showing {{ $configs->firstItem() ?? 0 }} to {{ $configs->lastItem() ?? 0 }} of {{ $configs->total() }} entries
            </small>
            {{ $configs->links() }}
        </div>
        @else
        <div class="alert alert-info">
            Please apply filters to see Final Mark Configurations.
        </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if($confirmingDeleteId)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
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
        @endif
    </div>
</div>