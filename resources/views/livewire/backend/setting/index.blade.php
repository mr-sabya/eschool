<div class="">
    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <div class="row">
            <!-- Form Column -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-primary">
                        <div class="card-title m-0 text-white">Update School Settings</div>
                    </div>
                    <div class="card-body">
                        {{-- Notification alert example, you can remove if you use JS notifications --}}
                        @if (session()->has('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                        @endif

                        {{-- School Name --}}
                        <div class="mb-3">
                            <label for="school_name">School Name *</label>
                            <input type="text" id="school_name" class="form-control @error('school_name') is-invalid @enderror" wire:model.defer="school_name">
                            @error('school_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- School Address --}}
                        <div class="mb-3">
                            <label for="school_address">School Address</label>
                            <input type="text" id="school_address" class="form-control" wire:model.defer="school_address">
                            @error('school_address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- School Email --}}
                        <div class="mb-3">
                            <label for="school_email">School Email</label>
                            <input type="email" id="school_email" class="form-control @error('school_email') is-invalid @enderror" wire:model.defer="school_email">
                            @error('school_email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- School Phone --}}
                        <div class="mb-3">
                            <label for="school_phone">School Phone</label>
                            <input type="text" id="school_phone" class="form-control @error('school_phone') is-invalid @enderror" wire:model.defer="school_phone">
                            @error('school_phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- School History --}}
                        <div class="mb-3">
                            <label for="school_history">School History</label>
                            <textarea id="school_history" class="form-control" wire:model.defer="school_history"></textarea>
                            @error('school_history') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- EIIN No --}}
                        <div class="mb-3">
                            <label for="eiin_no">EIIN No</label>
                            <input type="text" id="eiin_no" class="form-control @error('eiin_no') is-invalid @enderror" wire:model.defer="eiin_no">
                            @error('eiin_no') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- School Code --}}
                        <div class="mb-3">
                            <label for="school_code">School Code</label>
                            <input type="text" id="school_code" class="form-control @error('school_code') is-invalid @enderror" wire:model.defer="school_code">
                            @error('school_code') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Registration No --}}
                        <div class="mb-3">
                            <label for="registration_no">Registration No</label>
                            <input type="text" id="registration_no" class="form-control @error('registration_no') is-invalid @enderror" wire:model.defer="registration_no">
                            @error('registration_no') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>





                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-primary">
                        <div class="card-title m-0 text-white">Update Website Settings</div>
                    </div>
                    <div class="card-body">
                        {{-- Timezone --}}
                        <div class="mb-3">
                            <label for="timezone">Timezone *</label>
                            <input type="text" id="timezone" class="form-control @error('timezone') is-invalid @enderror" wire:model.defer="timezone">
                            @error('timezone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Copyright --}}
                        <div class="mb-3">
                            <label for="copyright">Copyright</label>
                            <input type="text" id="copyright" class="form-control @error('copyright') is-invalid @enderror" wire:model.defer="copyright">
                            @error('copyright') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Logo Upload --}}
                        <div class="mb-3">
                            <label>Logo</label>
                            <div class="image-preview">
                                @if ($new_logo)
                                <img src="{{ $new_logo->temporaryUrl() }}" alt="New Logo">
                                @elseif ($logo)
                                <img src="{{ asset('storage/' . $logo) }}" alt="Current Logo">
                                @endif
                            </div>
                            <input type="file" wire:model="new_logo" class="form-control @error('new_logo') is-invalid @enderror">
                            @error('new_logo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Favicon Upload --}}
                        <div class="mb-3">
                            <label>Favicon</label>
                            <div class="image-preview">
                                @if ($new_favicon)
                                <img src="{{ $new_favicon->temporaryUrl() }}" alt="New Favicon">
                                @elseif ($favicon)
                                <img src="{{ asset('storage/' . $favicon) }}" alt="Current Favicon">
                                @endif
                            </div>
                            <input type="file" wire:model="new_favicon" class="form-control @error('new_favicon') is-invalid @enderror">
                            @error('new_favicon') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>


                    </div>
                </div>
            </div>

            <!-- You can add other columns/content here if needed -->

        </div>
    </form>
</div>