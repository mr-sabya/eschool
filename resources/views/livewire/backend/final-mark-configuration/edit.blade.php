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