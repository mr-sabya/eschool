<div class="card">
    <div class="card-header bg-primary">
        <div class="card-title m-0 text-white">Promote Students</div>
    </div>

    <div class="card-body">
        <div class="row mb-4 border-bottom pb-4 align-items-end">
            {{-- From Class --}}
            <div class="col-md-6">
                <label for="from_class_id" class="form-label fw-bold">Promote From Class</label>
                <select wire:model.live="from_class_id" id="from_class_id" class="form-select">
                    <option value="">Select Class</option>
                    @foreach($allClasses as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- To Class --}}
            <div class="col-md-6">
                <label for="to_class_id" class="form-label fw-bold">Promote To</label>
                <select wire:model.live="to_class_id" id="to_class_id" class="form-select" @if(!$from_class_id) disabled @endif>
                    <option value="">Select Destination</option>
                    @foreach($promotionDestinations as $destination)
                    <option value="{{ $destination['id'] }}">{{ $destination['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(count($students) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px;"><input type="checkbox" wire:model.live="selectAll"></th>
                        <th>Current Roll</th>
                        <th>Name</th>

                        {{-- THIS IS THE CORRECT LOGIC YOU REQUESTED --}}
                        @if(optional($fromClassModel)->numeric_name == 8 && optional($toClassModel)->numeric_name == 9)
                        <th style="min-width: 200px;">Assign Department</th>
                        @endif

                        @if($to_class_id !== 'passed_out')
                        <th>New Roll Number</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $user)
                    <tr wire:key="student-{{ $user->id }}">
                        <td><input type="checkbox" wire:model.live="selected_students" value="{{ $user->id }}"></td>
                        <td>{{ $user->student->roll_number ?? 'N/A' }}</td>
                        <td>{{ $user->name }}</td>

                        {{-- THIS IS THE CORRECT LOGIC YOU REQUESTED --}}
                        @if(optional($fromClassModel)->numeric_name == 8 && optional($toClassModel)->numeric_name == 9)
                        <td>
                            <select class="form-select form-select-sm" wire:model="new_department_ids.{{ $user->id }}">
                                <option value="">Select Department...</option>
                                @foreach($allDepartments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        @endif

                        @if($to_class_id !== 'passed_out')
                        <td>
                            <input type="number" class="form-control form-control-sm" wire:model="new_roll_numbers.{{ $user->id }}" placeholder="Enter new roll">
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 text-end">
            @if($to_class_id && count($selected_students) > 0)
            <button wire:click="promoteSelectedStudents" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading wire:target="promoteSelectedStudents" class="spinner-border spinner-border-sm"></span>
                @if($to_class_id === 'passed_out')
                Confirm & Mark {{ count($selected_students) }} Student(s) as Passed Out
                @else
                Promote {{ count($selected_students) }} Student(s)
                @endif
            </button>
            @endif
        </div>
        @elseif($from_class_id)
        <div class="text-center text-muted mt-3">No active students found in the selected class to promote.</div>
        @endif
    </div>
</div>