<div>
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header bg-primary">
            <h5 class="card-title m-0 text-white">Select Exam</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label>Academic Session</label>
                    <select wire:model.live="selectedSessionId" class="form-select">
                        <option value="">Select Session</option>
                        @foreach($academicSessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Exam</label>
                    <select wire:model.live="selectedExamId" class="form-select" {{ !$selectedSessionId ? 'disabled' : '' }}>
                        <option value="">Select Exam</option>
                        @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->examCategory->name ?? 'Exam' }} ({{ \Carbon\Carbon::parse($exam->start_at)->format('d M, Y') }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    @if($selectedExamId)
    <div class="row">
        <!-- Form Column -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="card-title m-0 text-white">{{ $examRoutineId ? 'Edit' : 'Add' }} Schedule</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label">Class</label>
                            <select wire:model.live="school_class_id" class="form-select">
                                <option value="">Select Class</option>
                                @foreach($schoolClasses as $class) <option value="{{ $class->id }}">{{ $class->name }}</option> @endforeach
                            </select>
                            @error('school_class_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Section</label>
                            <select wire:model="class_section_id" class="form-select" {{ !$school_class_id ? 'disabled' : '' }}>
                                <option value="">Select Section</option>
                                @foreach($classSections as $section) <option value="{{ $section->id }}">{{ $section->name }}</option> @endforeach
                            </select>
                            @error('class_section_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department (Optional)</label>
                            <select wire:model.live="department_id" class="form-select" {{ !$school_class_id ? 'disabled' : '' }}>
                                <option value="">General Subjects</option>
                                @foreach($departments as $dept) <option value="{{ $dept->id }}">{{ $dept->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <select wire:model="subject_id" class="form-select" {{ !$school_class_id ? 'disabled' : '' }}>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject) <option value="{{ $subject->id }}">{{ $subject->name }}</option> @endforeach
                            </select>
                            @error('subject_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Exam Date</label>
                            <input type="date" wire:model="exam_date" class="form-control">
                            @error('exam_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Time Slot</label>
                            <select wire:model="time_slot_id" class="form-select">
                                <option value="">Select Time Slot</option>
                                @foreach($timeSlots as $slot) <option value="{{ $slot->id }}">{{ $slot->name }} ({{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }})</option> @endforeach
                            </select>
                            @error('time_slot_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Class Room</label>
                            <select wire:model="class_room_id" class="form-select">
                                <option value="">Select Room</option>
                                @foreach($classRooms as $room) <option value="{{ $room->id }}">{{ $room->name }}</option> @endforeach
                            </select>
                            @error('class_room_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button class="btn btn-primary" type="submit">{{ $examRoutineId ? 'Update' : 'Save' }}</button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="card-title m-0 text-white">Exam Schedule List</h5>
                </div>
                <div class="card-body">
                    <div class="table-action d-flex justify-content-between mb-3">
                        <div class="d-flex gap-2 align-items-center">
                            <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span>Records per page</span>
                        </div>
                        <div class="w-25">
                            <input type="text" class="form-control form-control-sm" wire:model.live.debounce.300ms="search" placeholder="Search by subject...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th wire:click="sortBy('exam_date')" style="cursor: pointer;">Date & Day @if($sortField === 'exam_date') <i class="ri-{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}-s-fill"></i> @endif</th>
                                    <th>Class & Section</th>
                                    <th>Subject</th>
                                    <th>Time</th>
                                    <th>Room</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($routines as $routine)
                                <tr wire:key="{{ $routine->id }}">
                                    <td>{{ $routine->exam_date->format('d M, Y') }}<br><small class="text-muted">{{ $routine->exam_date->format('l') }}</small></td>
                                    <td>{{ $routine->schoolClass->name }} ({{ $routine->classSection->name }})</td>
                                    <td>{{ $routine->subject->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($routine->timeSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($routine->timeSlot->end_time)->format('h:i A') }}</td>
                                    <td>{{ $routine->classRoom->name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $routine->id }})"><i class="ri-edit-line"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $routine->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="ri-delete-bin-line"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No schedule found for this exam.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $routines->links() }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to delete this schedule entry?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed" data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>