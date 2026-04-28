<div class="card shadow-sm">
    <div class="card-header bg-primary py-3">
        <h5 class="card-title m-0 text-white"><i class="fas fa-graduation-cap me-2"></i>Promote Students</h5>
    </div>

    <div class="card-body">
        <div class="row mb-4 border-bottom pb-4 align-items-end">
            {{-- 1. Source Class --}}
            <div class="col-md-4">
                <label class="form-label fw-bold text-primary">1. Current Class</label>
                <select wire:model.live="from_class_id" class="form-select border-primary">
                    <option value="">Select Class</option>
                    @foreach($allClasses as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 2. Target Class --}}
            <div class="col-md-4">
                <label class="form-label fw-bold text-success">2. Target Class</label>
                <select wire:model.live="to_class_id" class="form-select border-success" @if(!$from_class_id) disabled @endif>
                    <option value="">Select Destination</option>
                    @foreach($promotionDestinations as $destination)
                    <option value="{{ $destination['id'] }}">{{ $destination['name'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 3. Target Session --}}
            <div class="col-md-4">
                <label class="form-label fw-bold text-info">3. Target Session</label>
                <select wire:model="to_session_id" class="form-select border-info"
                    @if(!$from_class_id || $to_class_id==='passed_out' ) disabled @endif>
                    <option value="">Select Session</option>
                    @foreach($academicSessions as $session)
                    <option value="{{ $session->id }}">
                        {{ $session->name }} {{ $session->is_active ? '(Current)' : '' }}
                    </option>
                    @endforeach
                </select>
                @error('to_session_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>

        @if($from_class_id && count($students) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model.live="selectAll">
                            </div>
                        </th>
                        <th>Current Roll</th>
                        <th>Student Name</th>

                        {{-- Department selection (only if Class 8 to 9) --}}
                        @if(optional($fromClassModel)->numeric_name == 8 && optional($toClassModel)->numeric_name == 9)
                        <th style="min-width: 180px;">Assign Department</th>
                        @endif

                        @if($to_class_id !== 'passed_out')
                        <th style="width: 200px;">New Roll Number</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $user)
                    <tr wire:key="student-row-{{ $user->id }}">
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model.live="selected_students" value="{{ $user->id }}">
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">{{ $user->student->roll_number ?? 'N/A' }}</span></td>
                        <td class="fw-bold">{{ $user->name }}</td>

                        @if(optional($fromClassModel)->numeric_name == 8 && optional($toClassModel)->numeric_name == 9)
                        <td>
                            <select class="form-select form-select-sm border-warning" wire:model="new_department_ids.{{ $user->id }}">
                                <option value="">Select Department...</option>
                                @foreach($allDepartments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        @endif

                        @if($to_class_id !== 'passed_out')
                        <td>
                            <input type="number" class="form-control form-control-sm"
                                wire:model="new_roll_numbers.{{ $user->id }}"
                                placeholder="Auto: {{ optional($toClassModel)->numeric_name }}{{ substr($user->student->roll_number, strlen(optional($fromClassModel)->numeric_name)) }}">
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Selected: <strong>{{ count($selected_students) }}</strong> students
            </div>

            @if(count($selected_students) > 0 && $to_class_id)
            <button wire:click="promoteSelectedStudents" class="btn btn-lg {{ $to_class_id === 'passed_out' ? 'btn-danger' : 'btn-success' }}" wire:loading.attr="disabled">
                <span wire:loading wire:target="promoteSelectedStudents" class="spinner-border spinner-border-sm me-2"></span>
                @if($to_class_id === 'passed_out')
                <i class="fas fa-user-graduate me-2"></i> Confirm Graduation
                @else
                <i class="fas fa-arrow-circle-up me-2"></i> Promote {{ count($selected_students) }} Students
                @endif
            </button>
            @endif
        </div>
        @elseif($from_class_id)
        <div class="alert alert-info text-center shadow-sm py-4">
            <i class="fas fa-info-circle fa-2x mb-2 d-block"></i> No active students found in this class.
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="fas fa-users fa-4x mb-3"></i>
            <p class="fs-5">Select a source class to view students and begin promotion.</p>
        </div>
        @endif
    </div>

    {{-- 3. JavaScript Listener for Notifications --}}
    <script>
        window.addEventListener('notify', event => {
            // Livewire 3 property binding
            let type = event.detail.type || 'info';
            let message = event.detail.message || '';

            // Using standard alert if no library like Toastr is present
            if (window.toastr) {
                toastr[type](message);
            } else {
                alert(type.toUpperCase() + ": " + message);
            }
        });
    </script>
</div>