<div>
    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">Manage Institution History</div>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label">History Title (Bengali/English) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="title">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">History Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" wire:model="description" rows="10"></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- History Image -->
                        <div class="mb-3">
                            <label class="form-label">History Image</label>
                            <div class="image-preview mb-2">
                                @if ($new_image)
                                <img src="{{ $new_image->temporaryUrl() }}" alt="Preview" class="img-fluid rounded border" style="max-height: 200px;">
                                @elseif ($image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Current Image" class="img-fluid rounded border" style="max-height: 200px;">
                                @else
                                <div class="p-3 border rounded bg-light text-center">No image uploaded</div>
                                @endif
                            </div>
                            <input type="file" wire:model="new_image" class="form-control" accept="image/*">
                            @error('new_image') <span class="text-danger">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="new_image" class="text-muted mt-2">Uploading image...</div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove wire:target="save">Update History</span>
                                <span wire:loading wire:target="save">Saving...</span>
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
                    <h5>Quick Instructions</h5>
                    <p class="small text-muted">This section updates the "History" block on the homepage. Only one record is allowed for this section.</p>
                    <ul>
                        <li class="small">Recommended image ratio: 5:4</li>
                        <li class="small">Max file size: 2MB</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>