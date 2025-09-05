<div class="row">
    <!-- Left column: Form -->
    <div class="col-md-12">
        <div class="card shadow-sm rounded-3">

            <div class="card-header bg-primary">
                <div class="card-title m-0 text-white">Subject Attendance</div>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-lg-3">
                        <!-- Class -->
                        <div class="mb-3">
                            <label class="form-label">Class</label>
                            <select wire:model="school_class_id" wire:change="onClassChange($event.target.value)" class="form-select">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <!-- Section -->
                        <div class="mb-3">
                            <label class="form-label">Section</label>
                            <select wire:model="class_section_id" wire:change="onSectionChange($event.target.value)" class="form-select" @disabled(!$school_class_id)>
                                <option value="">-- Select Section --</option>
                                @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <!-- Shift -->
                        <div class="mb-3">
                            <label class="form-label">Shift</label>
                            <select wire:model="shift_id" wire:change="onShiftChange($event.target.value)" class="form-select">
                                <option value="">-- Select Shift --</option>
                                @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <!-- Department -->
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select wire:model="department_id" wire:change="onDepartmentChange($event.target.value)" class="form-select">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <!-- Subject -->
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <select wire:model="subject_id" wire:change="onSubjectChange($event.target.value)" class="form-select" @disabled(!$school_class_id)>
                                <option value="">-- Select Subject --</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <!-- Date -->
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" wire:model="attendance_date" wire:change="onDateChange($event.target.value)" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Right column: Student Table -->
    <div class="col-md-12">

        <div class="card shadow-sm rounded-3">
            <div class="card-header bg-primary">
                <div class="card-title m-0 text-white">Students</div>
            </div>
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <input type="text" class="form-control w-25" placeholder="Search..." wire:model.live="search">


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
                <table class="table table-striped table-bordered mb-0">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('roll_no')" style="cursor:pointer">Roll</th>
                            <th wire:click="sortBy('name')" style="cursor:pointer">Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>{{ $student->roll_number }}</td>
                            <td>{{ $student->user['name'] }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @foreach($statuses as $status)
                                    <button type="button"
                                        class="btn btn-outline-secondary {{ (isset($attendances[$student->id]) && $attendances[$student->id] === $status->value) ? 'active btn-success' : '' }}"
                                        wire:click="updateAttendance({{ $student->id }}, '{{ $status->value }}')">
                                        {{ ucfirst($status->value) }}
                                    </button>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No students found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>