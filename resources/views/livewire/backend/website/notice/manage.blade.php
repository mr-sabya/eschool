<div class="row">
    <div class="col-lg-8">
        <form wire:submit.prevent="save">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <div class="card-title m-0 text-white">{{ $noticeId ? 'Edit Notice' : 'Add Notice' }}</div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-12">
                            <!-- Title -->
                            <div class="mb-3">
                                <label>Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model="title">
                                @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Notice Type -->
                            <div class="mb-3">
                                <label>Notice Type</label>
                                <select class="form-select" wire:model="notice_type">
                                    <option value="general">General</option>
                                    <option value="exam">Exam</option>
                                    <option value="holiday">Holiday</option>
                                    <option value="event">Event</option>
                                </select>
                                @error('notice_type')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Start Date</label>
                                <input type="date" class="form-control" wire:model="start_date">
                                @error('start_date')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>End Date</label>
                                <input type="date" class="form-control" wire:model="end_date">
                                @error('end_date')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <!-- Description -->
                            <div class="mb-3">
                                <label>Description</label>
                                <livewire:quill-text-editor
                                    wire:model.live="description"
                                    theme="snow" />
                                @error('description')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>



                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Attachment</label>
                                <div>
                                    @if($new_attachment)
                                    <span class="d-block text-muted mb-1">New File: {{ $new_attachment->getClientOriginalName() }}</span>
                                    @elseif($attachment)
                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="d-block mb-1">
                                        Current File: {{ basename($attachment) }}
                                    </a>
                                    @endif
                                </div>

                                <input type="file" class="form-control" wire:model="new_attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('new_attachment')<small class="text-danger">{{ $message }}</small>@enderror

                                <div wire:loading wire:target="new_attachment" class="text-muted mt-2">Uploading...</div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <!-- Status -->
                            <div class="mb-3 d-flex align-items-center">
                                <input type="checkbox" class="form-check-input me-2" wire:model="is_active" id="is_active">
                                <label for="is_active" class="form-check-label">Active</label>
                                @error('is_active')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ $noticeId ? 'Update' : 'Save' }} Notice</button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>