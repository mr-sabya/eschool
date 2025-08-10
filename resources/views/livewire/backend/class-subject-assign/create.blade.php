<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title m-0 text-white">Assign Subjects to Class</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save">

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Class <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="school_class_id" wire:change="onClassChange">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('school_class_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Section</label>
                        <select class="form-select" wire:model="class_section_id">
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('class_section_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select class="form-select" wire:model="department_id">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Academic Session <span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="academic_session_id">
                            <option value="">Select Session</option>
                            @foreach($academicSessions as $session)
                            <option value="{{ $session->id }}">{{ $session->name }}</option>
                            @endforeach
                        </select>
                        @error('academic_session_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Subject <span class="text-danger">*</span></th>
                            <th>Include in Result</th>
                            <th style="width: 40px;">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $index => $row)
                        <tr>
                            <td>
                                <select class="form-select" wire:model="rows.{{ $index }}.subject_id">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error("rows.$index.subject_id") <small class="text-danger">{{ $message }}</small> @enderror
                            </td>
                            <td class="text-center">
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        wire:model="rows.{{ $index }}.is_added_to_result"
                                        id="is_added_to_result_{{ $index }}">
                                    <label class="form-check-label" for="is_added_to_result_{{ $index }}">
                                        Yes
                                    </label>
                                </div>
                            </td>
                            <td class="text-center">
                                @if(count($rows) > 1)
                                <button type="button" wire:click="removeRow({{ $index }})" class="btn btn-sm btn-danger" title="Remove">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" wire:click="addRow" class="btn btn-sm btn-secondary">+ Add Row</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>