<div>
    <!-- back to guardian list -->
    <div class="mb-2">
        <a href="{{ route('admin.guardian.index') }}" wire:navigate><i class="ri-arrow-left-line"></i> Back to Guardian List</a>
    </div>
    <form wire:submit.prevent="save">
        <div class="card mb-3">
            <div class="card-header top bg-primary text-white">
                <h4 class="m-0 text-white text-center">{{ $guardian_id ? 'Edit Guardian' : 'Add Guardian' }}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <div class="card-title m-0 text-white">Guardian's Login Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- User Info -->
                            <div class="col-md-12 mb-3">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="name" />
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" wire:model="email" />
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>
                                    Password
                                    @if(!$guardian_id)
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
                                    @if(!$guardian_id)
                                    <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="password" class="form-control" wire:model="password_confirmation" />
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
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <div class="card-title m-0 text-white">Guardian's Image</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Profile Picture -->
                            <div class="col-md-12 mb-1">
                                <label>Profile Picture</label>
                                <div class="image-preview">
                                    @if ($profile_picture)
                                    <img src="{{ asset('storage/'.$profile_picture) }}" alt="Profile Picture" class="img-thumbnail" />
                                    @endif
                                    @if ($new_profile_picture)
                                    <img src="{{ $new_profile_picture->temporaryUrl() }}" class="img-thumbnail mt-2" />
                                    @endif
                                </div>
                                <input type="file" wire:model="new_profile_picture" class="form-control" />
                                @error('new_profile_picture') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <div class="card-title m-0 text-white">Guardian's Information</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Guardian Info -->
                    <div class="col-md-6 mb-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" wire:model="phone" />
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Address</label>
                        <input type="text" class="form-control" wire:model="address" />
                        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Date of Birth</label>
                        <input type="date" class="form-control" wire:model="date_of_birth" />
                        @error('date_of_birth') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Occupation</label>
                        <input type="text" class="form-control" wire:model="occupation" />
                        @error('occupation') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>National ID</label>
                        <input type="text" class="form-control" wire:model="national_id" />
                        @error('national_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Place of Birth</label>
                        <input type="text" class="form-control" wire:model="place_of_birth" />
                        @error('place_of_birth') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nationality</label>
                        <input type="text" class="form-control" wire:model="nationality" />
                        @error('nationality') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Language</label>
                        <input type="text" class="form-control" wire:model="language" />
                        @error('language') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label>Notes</label>
                        <textarea class="form-control" wire:model="notes"></textarea>
                        @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">{{ $guardian_id ? 'Update' : 'Save' }}</button>
                <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
            </div>
        </div>
    </form>
</div>