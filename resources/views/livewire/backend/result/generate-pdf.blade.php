<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title m-0">Download Class Result PDF</h5>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="downloadPdf">
                <div class="row">
                    <div class="col-lg-4">
                        <label>Class</label>
                        <select wire:model="school_class_id" wire:change="loadSections($event.target.value)" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('school_class_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-lg-4">
                        <label>Section</label>
                        <select wire:model="class_section_id" class="form-select" required>
                            <option value="">Select Section</option>
                            @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('class_section_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-lg-4">
                        <label>Exam</label>
                        <select wire:model="exam_id" class="form-select" required>
                            <option value="">Select Exam</option>
                            @foreach ($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->examCategory->name ?? 'Exam' }} ({{ \Carbon\Carbon::parse($exam->start_at)->format('d M, Y') }})</option>
                            @endforeach
                        </select>
                        @error('exam_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <!-- Text while downloading -->
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin me-1"></i> Downloading...
                        </span>

                        <!-- Normal button text -->
                        <span wire:loading.remove>
                            <i class="fas fa-download me-1"></i> Download PDF
                        </span>
                </div>
            </form>
        </div>
    </div>
</div>