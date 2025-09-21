
<div>
    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary">
            <h5 class="card-title m-0 text-white">View Timetable</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label for="session_filter">Academic Session</label>
                    <select wire:model.live="selectedSessionId" id="session_filter" class="form-select">
                        <option value="">Select Session</option>
                        @foreach($academicSessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="class_filter">Class</label>
                    <select wire:model.live="selectedClassId" id="class_filter" class="form-select">
                        <option value="">Select Class</option>
                        @foreach($schoolClasses as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="section_filter">Section</label>
                    <select wire:model.live="selectedSectionId" id="section_filter" class="form-select" {{ !$selectedClassId ? 'disabled' : '' }}>
                        <option value="">Select Section</option>
                        @foreach($classSections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- NEW DEPARTMENT DROPDOWN (ONLY SHOWS WHEN CLASS IS SELECTED) -->
                @if($selectedClassId)
                <div class="col-md-3">
                    <label for="department_filter">Department</label>
                    <select wire:model.live="selectedDepartmentId" id="department_filter" class="form-select">
                        <option value="">General Subjects</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Timetable Card -->
    @if ($selectedSessionId && $selectedClassId && $selectedSectionId)
    <div class="card">
        <div class="card-header bg-primary">
            <h5 class="card-title m-0 text-white">Class Routine</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-light">
                            <th>Time</th>
                            @foreach ($days as $day)
                            <th>{{ $day->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($timeSlots as $slot)
                        <tr>
                            <td class="align-middle">
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                            </td>
                            @foreach ($days as $day)
                            @php
                            $key = $day->id . '-' . $slot->id;
                            $routineEntry = $routines[$key] ?? null;
                            @endphp
                            <td class="align-middle p-1">
                                @if ($routineEntry)
                                <div class="p-2 rounded bg-primary-light position-relative">
                                    <p class="fw-bold mb-0">{{ $routineEntry->subject->name }}</p>
                                    <small class="text-muted">{{ $routineEntry->staff->name }}</small>
                                    <div class="position-absolute top-0 end-0 p-1">
                                        <button class="btn btn-xs btn-primary py-0 px-1" wire:click="edit({{ $routineEntry->id }})"><i class="ri-edit-line"></i></button>
                                        <button class="btn btn-xs btn-danger py-0 px-1" wire:click="confirmDelete({{ $routineEntry->id }})"><i class="ri-delete-bin-line"></i></button>
                                    </div>
                                </div>
                                @else
                                <button class="btn btn-sm btn-soft-primary" wire:click="openModal({{ $day->id }}, {{ $slot->id }})">
                                    <i class="ri-add-line"></i> Add
                                </button>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">Please select an academic session, class, and section to view the routine.</div>
    @endif


    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="modal fade show" style="display: block;" tabindex="-1" aria-labelledby="routineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="routineModalLabel">{{ $routineId ? 'Edit' : 'Add' }} Routine Entry</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select wire:model="type" id="type" class="form-select">
                                <option value="class">Class</option>
                                <option value="exam">Exam</option>
                            </select>
                            @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="subject_id" class="form-label">Subject</label>
                            <select wire:model="subject_id" id="subject_id" class="form-select">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="staff_id" class="form-label">Teacher</label>
                            <select wire:model="staff_id" id="staff_id" class="form-select">
                                <option value="">Select Teacher</option>
                                @foreach($staff as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user['name'] }}</option>
                                @endforeach
                            </select>
                            @error('staff_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click.prevent="save">{{ $routineId ? 'Update' : 'Save' }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this routine entry? This cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed" data-bs-dismiss="modal">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        document.addEventListener('livewire:initialized', () => {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

            @this.on('open-delete-modal', () => {
                deleteModal.show();
            });
        });
    </script>
    @endscript
</div>