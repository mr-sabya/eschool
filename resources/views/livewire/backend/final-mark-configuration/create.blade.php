<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title m-0 text-white">Final Mark Configuration</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label">Select Exam<span class="text-danger">*</span></label>
                        <select class="form-select" wire:model="exam_id">
                            <option value="">-- Select Exam --</option>
                            @foreach($exams as $exam)
                            <option value="{{ $exam->id }}">
                                {{ $exam->examCategory->name ?? 'Exam' }} ({{ \Carbon\Carbon::parse($exam->start_at)->format('d M, Y') }})
                            </option>
                            @endforeach
                        </select>
                        @error('exam_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label">Select Class<span class="text-danger">*</span> </label>
                        <select class="form-select" wire:model="school_class_id">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('school_class_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label">Select Department</label>
                        <select class="form-select" wire:model="department_id">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Class Test Total</th>
                        <th>Other Parts Total</th>
                        <th>Final Result %</th>
                        <th>Grading Scale</th>
                        <th>Exclude from GPA</th>
                        <th style="width: 40px;">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $index => $row)
                    <tr>
                        <td>
                            <select class="form-select" wire:model="rows.{{ $index }}.subject_id">
                                <option value="">-- Select --</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error("rows.$index.subject_id") <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control" wire:model="rows.{{ $index }}.class_test_total" />
                            @error("rows.$index.class_test_total") <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control" wire:model="rows.{{ $index }}.other_parts_total" />
                            @error("rows.$index.other_parts_total") <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                        <td>
                            <input type="number" class="form-control" wire:model="rows.{{ $index }}.final_result_weight_percentage" />
                            @error("rows.$index.final_result_weight_percentage") <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                        <td>
                            <select class="form-select" wire:model="rows.{{ $index }}.grading_scale">
                                <option value="100">100</option>
                                <option value="50">50</option>
                            </select>
                            @error("rows.$index.grading_scale") <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                        <td class="text-center d-flex align-items-center gap-2">
                            <input type="checkbox" id="exclude_gpa_{{ $index }}" wire:model="rows.{{ $index }}.exclude_from_gpa" />
                            <label for="exclude_gpa_{{ $index }}" class="mb-0">Not Include in GPA</label>
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
                    <i class="fas fa-save me-1"></i> Save Configurations
                </button>
            </div>
        </div>
    </div>
</div>