<div>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-white">Add Student Marks</h5>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="classSelect" class="form-label">Class</label>
                    <select id="classSelect" wire:model="schoolClassId" wire:change="onClassChange" class="form-select">
                        <option value="">-- Select Class --</option>
                        @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('schoolClassId') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-3">
                    <label for="sectionSelect" class="form-label">Section</label>
                    <select id="sectionSelect" wire:model="classSectionId" wire:change="onSectionChange" class="form-select" {{ $sections->isEmpty() ? 'disabled' : '' }}>
                        <option value="">-- Select Section --</option>
                        @foreach ($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('classSectionId') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select wire:model="departmentId" wire:change="onDepartmentChange" class="form-select">
                        <option value="">Select Department (optional)</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('departmentId') <span class="text-danger">{{ $message }}</span> @enderror
                </div>


                <div class="col-md-3">
                    <label for="subjectSelect" class="form-label">Subject</label>
                    <select id="subjectSelect" wire:model="subjectId" wire:change="onSubjectChange" class="form-select" {{ $subjects->isEmpty() ? 'disabled' : '' }}>
                        <option value="">-- Select Subject --</option>
                        @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subjectId') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-3">
                    <label for="examSelect" class="form-label">Exam</label>
                    <select id="examSelect" wire:model="examId" wire:change="onExamChange" class="form-select" {{ $exams->isEmpty() ? 'disabled' : '' }}>
                        <option value="">-- Select Exam --</option>
                        @foreach ($exams as $exam)
                        <option value="{{ $exam['id'] }}">{{ $exam['name'] }}</option>
                        @endforeach
                    </select>
                    @error('examId') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            @if ($students->count() && $markDistributions->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Roll Number</th>
                            <th>Student Name</th>
                            @foreach ($markDistributions as $md)
                            <th style="width: 300px;">
                                {{ $md->markDistribution->name }}
                                <br>
                                <small>Max: {{ $md->mark }}</small>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->roll_number }}</td>
                            <td>{{ $student->user->name }}</td>
                            @foreach ($markDistributions as $md)
                            <td style="width: 120px;">
                                <div class="d-flex align-items-center gap-1">
                                    <input
                                        type="number"
                                        min="0"
                                        max="{{ $md->mark }}"
                                        step="0.01"
                                        wire:model.defer="marks.{{ $student->id }}.{{ $md->mark_distribution_id }}.marks_obtained"
                                        class="form-control form-control-sm"
                                        :disabled="$wire.marks[{{ $student->id }}][{{ $md->mark_distribution_id }}]?.is_absent">

                                    <div class="form-check ms-1">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            wire:model.defer="marks.{{ $student->id }}.{{ $md->mark_distribution_id }}.is_absent"
                                            id="absent_{{ $student->id }}_{{ $md->mark_distribution_id }}">
                                        <label class="form-check-label small" for="absent_{{ $student->id }}_{{ $md->mark_distribution_id }}">
                                            Absent
                                        </label>
                                    </div>
                                </div>

                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button wire:click="save" class="btn btn-primary">Save Marks</button>
            </div>
            @elseif ($schoolClassId && $classSectionId && $subjectId && $examId)
            <div class="alert alert-warning mt-3">
                No students or mark distributions found for selected options.
            </div>
            @endif
        </div>
    </div>
</div>