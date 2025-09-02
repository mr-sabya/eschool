<div>
    <div class="row">
        <!-- Form Column -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">{{ $banner_id ? 'Edit Banner' : 'Add Banner' }}</div>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <!-- Title -->
                        <div class="mb-3">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="title">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Text -->
                        <div class="mb-3">
                            <label>Text</label>
                            <textarea class="form-control" wire:model="text"></textarea>
                            @error('text') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Order -->
                        <div class="mb-3">
                            <label>Order</label>
                            <input type="number" class="form-control" wire:model="order">
                            @error('order') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>



                        <!-- Banner Image -->
                        <div class="mb-3">
                            <label>Banner Image (1586 × 702 px)</label>
                            <div class="image-preview">
                                @if ($new_image)
                                <img src="{{ $new_image->temporaryUrl() }}" alt="New Banner Image" class="img-fluid rounded border">
                                @elseif ($image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Banner Image" class="img-fluid rounded border">
                                @endif
                            </div>
                            <input type="file" wire:model="new_image" class="form-control" accept="image/*">
                            @error('new_image') <span class="text-danger">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="new_image" class="text-muted mt-2">Uploading...</div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" wire:model="is_active" id="is_active">
                            <label class="form-check-label" for="is_active">Active</label>
                            @error('is_active') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ $banner_id ? 'Update' : 'Save' }}</button>
                            <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>