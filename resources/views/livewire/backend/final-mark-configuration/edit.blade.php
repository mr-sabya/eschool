<div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title m-0 text-white">Edit Final Mark Configuration</h5>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="update">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Class</label>
                                <select class="form-select" wire:model="school_class_id">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('school_class_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Department (Optional)</label>
                                <select class="form-select" wire:model="department_id">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Subject</label>
                                <select class="form-select" wire:model="subject_id">
                                    <option value="">Select Subject</option>
                                    @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Class Test Total</label>
                                <input type="number" wire:model="class_test_total" class="form-control" min="0">
                                @error('class_test_total') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Other Parts Total</label>
                                <input type="number" wire:model="other_parts_total" class="form-control" min="0">
                                @error('other_parts_total') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Final Result Weight (%)</label>
                                <input type="number" wire:model="final_result_weight_percentage" class="form-control" min="0" max="100">
                                @error('final_result_weight_percentage') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>


                            <div class="col-md-12 mb-3">
                                <label class="form-label">Grading Scale</label>
                                <select class="form-select" wire:model="grading_scale">
                                    <option value="">Select Grading Scale</option>
                                    <option value="100">100 Marks</option>
                                    <option value="50">50 Marks</option>
                                </select>
                                @error('grading_scale') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>


                            <div class="col-md-12 mb-3 d-flex align-items-center gap-2">
                                <input
                                    type="checkbox"
                                    id="exclude_from_gpa"
                                    wire:model="exclude_from_gpa"
                                    class="form-check-input" />
                                <label for="exclude_from_gpa" class="form-check-label mb-0">
                                    Not Include in GPA
                                </label>
                            </div>
                        </div>


                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>