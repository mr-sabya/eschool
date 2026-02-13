<div>
    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">Manage Admission Details</div>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">

                        <!-- Admission Content (HTML/Text) -->
                        <div class="mb-3">
                            <label class="form-label">Admission Content (HTML allowed) <span class="text-danger">*</span></label>
                            <textarea class="form-control" wire:model="content" rows="15" placeholder="Enter HTML or plain text here..."></textarea>
                            @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="text-muted">You can paste your HTML structure here for better styling.</small>
                        </div>

                        <!-- Admission Form PDF -->
                        <div class="mb-3">
                            <label class="form-label">Admission Form (PDF/Doc)</label>
                            <div class="file-status mb-2">
                                @if ($new_form)
                                <div class="alert alert-info py-2">
                                    <i class="fas fa-file-upload"></i> New file ready to upload: {{ $new_form->getClientOriginalName() }}
                                </div>
                                @elseif ($form_path)
                                <div class="alert alert-success py-2">
                                    <i class="fas fa-check-circle"></i> Current File:
                                    <a href="{{ asset('storage/' . $form_path) }}" target="_blank" class="text-decoration-underline text-success">View Existing Form</a>
                                </div>
                                @else
                                <div class="p-3 border rounded bg-light text-center">No form uploaded yet</div>
                                @endif
                            </div>

                            <input type="file" wire:model="new_form" class="form-control" accept=".pdf,.doc,.docx">
                            @error('new_form') <span class="text-danger">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="new_form" class="text-primary mt-2">
                                <i class="fas fa-spinner fa-spin"></i> Uploading to server...
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove wire:target="save">Update Admission Info</span>
                                <span wire:loading wire:target="save">Saving Changes...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Helpful Info Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="fas fa-info-circle"></i> Instructions</h5>
                    <hr>
                    <p class="small">This section controls the public <strong>Admission Information</strong> page.</p>
                    <ul class="ps-3">
                        <li class="small mb-2"><strong>Content:</strong> You can use standard HTML tags like <code>&lt;h2&gt;</code>, <code>&lt;ul&gt;</code>, and <code>&lt;li&gt;</code> to style the information.</li>
                        <li class="small mb-2"><strong>File Format:</strong> Only PDF, DOC, and DOCX files are allowed.</li>
                        <li class="small mb-2"><strong>Max Size:</strong> 5MB per file.</li>
                    </ul>
                    <div class="alert alert-warning small">
                        <strong>Note:</strong> Updating this will immediately change the info for all website visitors.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>