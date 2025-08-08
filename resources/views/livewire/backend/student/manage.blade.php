<form wire:submit.prevent="save">
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <div class="card-title m-0 text-white">{{ $student_id ? 'Edit Student' : 'Add Student' }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <div class="card-title m-0 text-white">Student's Login Info</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- User Info -->
                        <div class="col-md-12 mb-3">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="name">
                            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Username</label>
                            <input type="text" class="form-control" wire:model="username">
                            @error('username')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        @if(!$student_id)
                        <div class="col-md-12 mb-3">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" wire:model="password">
                            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        @endif
                        <div class="col-md-12">
                            <input type="checkbox" class="form-check-input" wire:model="is_active" id="is_active">
                            <label for="is_active" class="form-check-label">Active</label>
                            @error('is_active')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <div class="card-title m-0 text-white">Student's Image</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Profile Picture -->
                        <div class="col-md-12 mb-1">
                            <label>Profile Picture</label>
                            <div class="image-preview">
                                @if ($profile_picture)
                                <img src="{{ asset('storage/'.$profile_picture) }}" alt="Profile Picture">
                                @endif
                            </div>
                            <input type="file" wire:model="new_profile_picture" class="form-control">
                            @error('new_profile_picture')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <div class="card-title m-0 text-white">Student's Information</div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Student Info -->
                <div class="col-md-3 mb-3">
                    <label>Admission Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model="admission_number">
                    @error('admission_number')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Admission Date</label>
                    <input type="date" class="form-control" wire:model="admission_date">
                    @error('admission_date')<small class="text-danger">{{ $message }}</small>@enderror
                </div>


                <!-- academic session select option-->
                <div class="col-md-3 mb-3">
                    <label>Academic Session <span class="text-danger">*</span></label>
                    <select class="form-select" wire:model="academic_session_id">
                        <option value="">Select Academic Session</option>
                        @foreach($academic_sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                    @error('academic_session_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>


                <div class="col-md-3 mb-3">
                    <label>Class <span class="text-danger">*</span></label>
                    <select wire:model="school_class_id" wire:change="loadSections($event.target.value)" class="form-control">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('school_class_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label>Section</label>
                    <select class="form-select" wire:model="class_section_id">
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('class_section_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <!-- department_id -->
                <div class="col-md-3 mb-3">
                    <label>Department</label>
                    <select class="form-select" wire:model="department_id">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Shift</label>
                    <select class="form-select" wire:model="shift_id">
                        <option value="">Select Shift</option>
                        @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </select>
                    @error('shift_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Roll Number</label>
                    <input type="text" class="form-control" wire:model="roll_number">
                    @error('roll_number')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Guardian</label>
                    <select class="form-select" wire:model="guardian_id">
                        <option value="">Select Guardian</option>
                        @foreach($guardians as $guardian)
                        <option value="{{ $guardian->id }}">{{ $guardian->name }}</option>
                        @endforeach
                    </select>
                    @error('guardian_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Phone</label>
                    <input type="text" class="form-control" wire:model="phone">
                    @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label>Address</label>
                    <input type="text" class="form-control" wire:model="address">
                    @error('address')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label>Date of Birth</label>
                    <input type="date" class="form-control" wire:model="date_of_birth">
                    @error('date_of_birth')<small class="text-danger">{{ $message }}</small>@enderror
                </div>


                <!-- Continue with more inputs for category, gender, blood, religion, national_id etc. -->

                <div class="col-md-3 mb-3">
                    <label>Category</label>
                    <select class="form-select" wire:model="category">
                        <option value="">Select Category</option>
                        <option value="regular">Regular</option>
                        <option value="irregular">Irregular</option>
                    </select>
                    @error('category')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Gender</label>
                    <select class="form-select" wire:model="gender_id">
                        <option value="">Select Gender</option>
                        @foreach($genders as $gender)
                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                        @endforeach
                    </select>
                    @error('gender_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Blood Group</label>
                    <select class="form-select" wire:model="blood_id">
                        <option value="">Select Blood Group</option>
                        @foreach($bloods as $blood)
                        <option value="{{ $blood->id }}">{{ $blood->name }}</option>
                        @endforeach
                    </select>
                    @error('blood_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Religion</label>
                    <select class="form-select" wire:model="religion_id">
                        <option value="">Select Religion</option>
                        @foreach($religions as $religion)
                        <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                        @endforeach
                    </select>
                    @error('religion_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>National ID</label>
                    <input type="text" class="form-control" wire:model="national_id">
                    @error('national_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Place of Birth</label>
                    <input type="text" class="form-control" wire:model="place_of_birth">
                    @error('place_of_birth')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Nationality</label>
                    <input type="text" class="form-control" wire:model="nationality">
                    @error('nationality')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Language</label>
                    <input type="text" class="form-control" wire:model="language">
                    @error('language')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Health Status</label>
                    <input type="text" class="form-control" wire:model="health_status">
                    @error('health_status')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Rank in Family</label>
                    <input type="number" class="form-control" wire:model="rank_in_family">
                    @error('rank_in_family')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Number of Siblings</label>
                    <input type="number" class="form-control" wire:model="number_of_siblings">
                    @error('number_of_siblings')<small class="text-danger">{{ $message }}</small>@enderror
                </div>



                <div class="col-md-3 mb-3">
                    <label>Emergency Contact Name</label>
                    <input type="text" class="form-control" wire:model="emergency_contact_name">
                    @error('emergency_contact_name')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label>Emergency Contact Phone</label>
                    <input type="text" class="form-control" wire:model="emergency_contact_phone">
                    @error('emergency_contact_phone')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <!-- previous_school_attended select option -->
                    <label for="previous_school_attended">Previous School Attended</label>
                    <select class="form-control" wire:model.live="previous_school_attended" id="previous_school_attended">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                @if($previous_school_attended == 1)
                <div class="col-md-3 mb-3">
                    <label>Previous School</label>
                    <input type="text" class="form-control" wire:model="previous_school">
                    @error('previous_school')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label>Previous School Document</label>
                    <input type="file" class="form-control" wire:model="previous_school_document">
                    @error('previous_school_document')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                @endif


                <div class="col-12 mb-3">
                    <label>Notes</label>
                    <textarea class="form-control" wire:model="notes"></textarea>
                    @error('notes')<small class="text-danger">{{ $message }}</small>@enderror
                </div>


            </div>

            <button type="submit" class="btn btn-primary">{{ $student_id ? 'Update' : 'Save' }}</button>
            <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
        </div>
    </div>
</form>