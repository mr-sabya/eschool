<div class="row">
    <div class="col-lg-8">
        <form wire:submit.prevent="save">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <div class="card-title m-0 text-white">{{ $memberId ? 'Edit' : 'Add' }} Library Member</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Member ID -->
                        <div class="col-lg-12 mb-3">
                            <label for="memberId" class="form-label">Member ID <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <div class="flex-grow-1">
                                    <input type="text" id="memberId" class="form-control" wire:model="member_id" placeholder="Enter Member ID">
                                    @error('member_id')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <button type="button" class="btn btn-secondary" wire:click="generateMemberId">
                                    <i class="ri-refresh-line"></i> Generate ID
                                </button>
                            </div>
                        </div>

                        <!-- Member Category -->
                        <div class="col-md-6 mb-3">
                            <label>Member Category <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="member_category_id">
                                <option value="">Select Member Category</option>
                                @foreach($member_categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('member_category_id')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- User Type -->
                        <div class="col-md-6 mb-3">
                            <label>User Type <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="user_type" wire:change="userTypeChanged($event.target.value)">
                                <option value="">Select Type</option>
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                            </select>
                            @error('user_type')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Student Fields -->
                        @if($user_type === 'student')
                        <div class="col-md-6 mb-3">
                            <label>Class <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="class_id" wire:change="classChanged($event.target.value)">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Section <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="section_id" wire:change="sectionChanged($event.target.value)">
                                <option value="">Select Section</option>
                                @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Student <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="user_id">
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->username }})</option>
                                @endforeach
                            </select>
                        </div>
                        @elseif($user_type === 'teacher')
                        <div class="col-md-6 mb-3">
                            <label>Teacher <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="user_id">
                                <option value="">Select Teacher</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->username }})</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Dates -->
                        <div class="col-md-6 mb-3">
                            <label>Join Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="join_date">
                            @error('join_date')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Expire Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="expire_date">
                            @error('expire_date')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" wire:model="status" id="isActive">
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ $memberId ? 'Update' : 'Save' }} Member</button>
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>