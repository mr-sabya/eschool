<div>
    <!-- back to staff list -->
    <div class="mb-2">
        <a href="{{ route('admin.staff.index') }}" wire:navigate><i class="ri-arrow-left-line"></i> Back to Staff List</a>
    </div>

    <form wire:submit.prevent="save">
        <div class="card mb-3">
            <div class="card-header top bg-primary text-white">
                <h4 class="m-0 text-white text-center">{{ $staff_id ? 'Edit Staff' : 'Add Staff' }}</h4>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: User Info -->
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <div class="card-title m-0 text-white">User Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="name" />
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" wire:model="email" />
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>
                                    Password
                                    @if(!$user_id)
                                    <span class="text-danger">*</span>
                                    @else
                                    <small>(Leave blank to keep current)</small>
                                    @endif
                                </label>
                                <input type="password" class="form-control" wire:model="password" />
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>
                                    Confirm Password
                                    @if(!$user_id)
                                    <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="password" class="form-control" wire:model="password_confirmation" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Role <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model="role">
                                    <option value="">-- Select Role --</option>
                                    <option value="admin">Admin</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="librarian">Librarian</option>
                                    <option value="accountant">Accountant</option>
                                    <option value="staff">Staff</option>
                                </select>
                                @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-12">
                                <input type="checkbox" class="form-check-input" wire:model="status" id="is_active" />
                                <label for="is_active" class="form-check-label">Active</label>
                                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Profile Picture -->
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <div class="card-title m-0 text-white">Profile Picture</div>
                    </div>
                    <div class="card-body">
                        <label class="form-label">Upload Profile Picture</label>
                        <div class="image-preview">
                            @if ($profile_picture)
                            <img src="{{ asset('storage/' . $profile_picture) }}" alt="Profile Picture" class="img-thumbnail" />
                            @endif
                            @if ($new_profile_picture)
                            <img src="{{ $new_profile_picture->temporaryUrl() }}" alt="New Profile Picture" class="img-thumbnail mt-2" />
                            @endif
                        </div>
                        <input type="file" wire:model="new_profile_picture" class="form-control" />
                        @error('new_profile_picture') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Info Full Width Card -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <div class="card-title m-0 text-white">Staff Information</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Staff ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="staffUID" />
                        @error('staffUID') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Department</label>
                        <select class="form-select" wire:model="department_id">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="first_name" />
                        @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" wire:model="last_name" />
                        @error('last_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Father's Name</label>
                        <input type="text" class="form-control" wire:model="father_name" />
                        @error('father_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Mother's Name</label>
                        <input type="text" class="form-control" wire:model="mother_name" />
                        @error('mother_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" wire:model="phone" />
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>NID</label>
                        <input type="text" class="form-control" wire:model="nid" />
                        @error('nid') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Date of Birth</label>
                        <input type="date" class="form-control" wire:model="date_of_birth" />
                        @error('date_of_birth') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Current Address</label>
                        <input type="text" class="form-control" wire:model="current_address" />
                        @error('current_address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Permanent Address</label>
                        <input type="text" class="form-control" wire:model="permanent_address" />
                        @error('permanent_address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Designation</label>
                        <select class="form-select" wire:model="designation_id">
                            <option value="">-- Select Designation --</option>
                            @foreach($designations as $designation)
                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                            @endforeach
                        </select>
                        @error('designation_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Gender</label>
                        <select class="form-select" wire:model="gender_id">
                            <option value="">-- Select Gender --</option>
                            @foreach($genders as $gender)
                            <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                            @endforeach
                        </select>
                        @error('gender_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Marital Status</label>
                        <select class="form-select" wire:model="marital_status">
                            <option value="single" {{ $marital_status === 'single' ? 'selected' : '' }}>Single</option>
                            <option value="married" {{ $marital_status === 'married' ? 'selected' : '' }}>Married</option>
                            <option value="divorced" {{ $marital_status === 'divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="widowed" {{ $marital_status === 'widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        @error('marital_status') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Basic Salary</label>
                        <input type="number" step="0.01" class="form-control" wire:model="basic_salary" />
                        @error('basic_salary') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Date of Joining</label>
                        <input type="date" class="form-control" wire:model="date_of_joining" />
                        @error('date_of_joining') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">{{ $staff_id ? 'Update' : 'Save' }}</button>
                <button type="button" wire:click="resetForm" class="btn btn-secondary ms-2">Reset</button>
            </div>
        </div>
    </form>
</div>