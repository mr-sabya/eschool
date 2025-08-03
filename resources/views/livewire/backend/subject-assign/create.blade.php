<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title m-0 text-white">Assign Subjects to Class</h5>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Select Class</label>
                    <select class="form-select" wire:model="school_class_id" wire:change="onClassChange($event.target.value)">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('school_class_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Select Section</label>
                    <select class="form-select" wire:model="class_section_id" {{ $sections->isEmpty() ? 'disabled' : '' }}>
                        <option value="">-- Select Section --</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('class_section_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Select Shift (Optional)</label>
                    <select class="form-select" wire:model="shift_id">
                        <option value="">-- Select Shift --</option>
                        @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </select>
                    @error('shift_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Teacher (Optional)</th>
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

            <div class="d-flex justify-content-between">
                <button wire:click.prevent="addRow" class="btn btn-sm btn-secondary">
                    + Add Row
                </button>
                <button wire:click.prevent="save" class="btn btn-sm btn-primary">
                    <i class="fas fa-save me-1"></i> Save Assignments
                </button>
            </div>
        </div>
    </div>
</div>