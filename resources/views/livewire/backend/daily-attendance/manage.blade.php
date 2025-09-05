<div class="row">
    <!-- Filters -->
    <div class="col-md-12">
        <div class="card shadow-sm rounded-3">
            <div class="card-header bg-primary">
                <div class="card-title m-0 text-white">Daily Attendance</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <label>Class</label>
                        <select class="form-select" wire:model="school_class_id" wire:change="onClassChange($event.target.value)">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Section</label>
                        <select class="form-select" wire:model="class_section_id" wire:change="onSectionChange($event.target.value)" @disabled(!$school_class_id)>
                            <option value="">-- Select Section --</option>
                            @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Shift</label>
                        <select class="form-select" wire:model="shift_id" wire:change="onShiftChange($event.target.value)">
                            <option value="">-- Select Shift --</option>
                            @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Department</label>
                        <select class="form-select" wire:model="department_id" wire:change="onDepartmentChange($event.target.value)">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 mt-3">
                        <label>Date</label>
                        <input type="date" class="form-control" wire:model="attendance_date" wire:change="onDateChange($event.target.value)">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="col-md-12 mt-3">
        <div class="card shadow-sm rounded-3">
            <div class="card-header bg-primary">
                <div class="card-title m-0 text-white">Students</div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <input type="text" class="form-control w-25" placeholder="Search..." wire:model.debounce.500ms="search">

                    @if($students->count())
                    <div class="mb-2 d-flex justify-content-end align-items-center">
                        <span class="me-2">Mark All:</span>
                        <div class="btn-group btn-group-sm">
                            @foreach($statuses as $status)
                            <button type="button" class="btn btn-outline-primary"
                                wire:click="markAll('{{ $status->value }}')">
                                {{ ucfirst($status->value) }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th wire:click="sortBy('roll_number')" style="cursor:pointer">Roll</th>
                            <th wire:click="sortBy('name')" style="cursor:pointer">Name</th>
                            <th>Class (Section)</th>
                            <th>Status</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->roll_number }}</td>
                            <td>{{ $student->user->name }}</td>
                            <td>{{ $student->schoolClass->name }} ({{ $student->classSection->name }})</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @foreach($statuses as $status)
                                    <button type="button"
                                        class="btn btn-outline-secondary {{ (isset($attendances[$student->id]) && $attendances[$student->id] === $status->value) ? 'active btn-success' : '' }}"
                                        wire:click="updateAttendance({{ $student->id }}, '{{ $status->value }}')">
                                        {{ ucfirst($status->value) }}
                                    </button>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <input type="text" class="form-control form-control-sm" wire:model.lazy="notes.{{ $student->id }}" placeholder="Add note...">
                                    <!-- add button -->
                                    <button class="btn btn-sm btn-primary" wire:click="saveNote({{ $student->id }})">Add</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No students found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>