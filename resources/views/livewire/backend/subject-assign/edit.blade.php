<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title m-0 text-white">Edit Subject Assign</h5>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <!-- Class -->
                <div class="col-md-3">
                    <label class="form-label">Class</label>
                    <select class="form-select" wire:model="school_class_id" wire:change="updatedSchoolClassIdManually">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('school_class_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Section -->
                <div class="col-md-3">
                    <label class="form-label">Section</label>
                    <select class="form-select" wire:model="class_section_id" {{ $sections->isEmpty() ? 'disabled' : '' }}>
                        <option value="">-- Select Section --</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('class_section_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Shift -->
                <div class="col-md-3">
                    <label class="form-label">Shift</label>
                    <select class="form-select" wire:model="shift_id">
                        <option value="">-- Select Shift --</option>
                        @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </select>
                    @error('shift_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Department -->
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select class="form-select" wire:model="department_id">
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Subjects Table -->
            <table class="table table-bordered mt-4">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th style="width: 40px;">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $index => $row)
                    <tr>
                        <td>
                            <select class="form-select" wire:model="rows.{{ $index }}.subject_id">
                                <option value="">-- Select Subject --</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error("rows.$index.subject_id") <small class="text-danger">{{ $message }}</small> @enderror
                        </td>

                        <td>
                            <select class="form-select" wire:model="rows.{{ $index }}.teacher_id">
                                <option value="">-- Select Teacher --</option>
                                @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            @error("rows.$index.teacher_id") <small class="text-danger">{{ $message }}</small> @enderror
                        </td>

                        <td class="text-center">
                            <button class="btn btn-sm btn-danger" wire:click.prevent="removeRow({{ $index }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Buttons -->
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-sm btn-secondary" wire:click.prevent="addRow">+ Add Row</button>
                <button class="btn btn-sm btn-primary" wire:click.prevent="save">
                    <i class="fas fa-save me-1"></i> Update Assignment
                </button>
            </div>
        </div>
    </div>
</div>