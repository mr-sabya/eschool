<div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title m-0 text-white">Edit Subject Mark Distribution</h5>
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
                                <label class="form-label">Section</label>
                                <select class="form-select" wire:model="class_section_id">
                                    <option value="">Select Section</option>
                                    @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_section_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Department</label>
                                <select class="form-select" wire:model="department_id">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
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
                                <label class="form-label">Mark Distribution</label>
                                <select class="form-select" wire:model="mark_distribution_id">
                                    <option value="">Select Mark Type</option>
                                    @foreach ($distributions as $dist)
                                    <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                    @endforeach
                                </select>
                                @error('mark_distribution_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Full Mark</label>
                                <input type="number" class="form-control" wire:model="mark">
                                @error('mark') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Pass Mark</label>
                                <input type="number" class="form-control" wire:model="pass_mark">
                                @error('pass_mark') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>