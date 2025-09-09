<form wire:submit.prevent="save">
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            <div class="card-title m-0 text-white">{{ $bookId ? 'Edit Book' : 'Add Book' }}</div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Book Info -->
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <div class="card-title m-0 text-white">Book Information</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="title">
                            @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Author -->
                        <div class="col-md-6 mb-3">
                            <label>Author <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="author">
                            @error('author')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- ISBN -->
                        <div class="col-md-6 mb-3">
                            <label>ISBN</label>
                            <input type="text" class="form-control" wire:model="isbn">
                            @error('isbn')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label>Category <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="book_category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('book_category_id')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Publisher -->
                        <div class="col-md-6 mb-3">
                            <label>Publisher</label>
                            <input type="text" class="form-control" wire:model="publisher">
                            @error('publisher')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Published Year -->
                        <div class="col-md-6 mb-3">
                            <label>Published Year</label>
                            <input type="number" class="form-control" wire:model="published_year" min="1900" max="{{ date('Y') }}">
                            @error('published_year')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-6 mb-3">
                            <label>Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model="quantity" min="0">
                            @error('quantity')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Available Quantity -->
                        <div class="col-md-6 mb-3">
                            <label>Available Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model="available_quantity" min="0">
                            @error('available_quantity')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Shelf Location -->
                        <div class="col-md-6 mb-3">
                            <label>Shelf Location</label>
                            <input type="text" class="form-control" wire:model="shelf_location">
                            @error('shelf_location')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Book Cover -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <div class="card-title m-0 text-white">Book Cover</div>
                </div>
                <div class="card-body">
                    <div class="col-md-12 mb-3">
                        <label>Cover Image</label>
                        <div class="image-preview">
                            @if ($new_cover_image)
                            <img src="{{ $new_cover_image->temporaryUrl() }}" alt="Cover" style="max-width:150px;">
                            @elseif($cover_image)
                            <img src="{{ asset('storage/'.$cover_image) }}" alt="Cover" style="max-width:150px;">
                            @endif
                        </div>
                        <input type="file" wire:model="new_cover_image" class="form-control">
                        @error('new_cover_image')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">{{ $bookId ? 'Update' : 'Save' }} Book</button>
        <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
    </div>
</form>