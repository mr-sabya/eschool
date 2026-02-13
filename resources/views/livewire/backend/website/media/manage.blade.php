<div class="">
    <div class="row">
        <!-- Form Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">Add Photo/Video</div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" wire:model.live="type">
                                <option value="photo">Photo</option>
                                <option value="video">Video</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" wire:model="title">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category (Optional)</label>
                            <input type="text" class="form-control" wire:model="category" placeholder="e.g. Campus, Sports">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload File (Max: {{ $type == 'photo' ? '2MB' : '20MB' }})</label>
                            <input type="file" class="form-control" wire:model="file">
                            @error('file') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <span wire:loading.remove wire:target="save">Upload Media</span>
                            <span wire:loading wire:target="save">Uploading...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- List Section -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Media Gallery Items</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Type</th>
                                <th>Title/Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    @if($item->type == 'photo')
                                    <img src="{{ asset('storage/'.$item->file_path) }}" width="80" class="rounded">
                                    @else
                                    <span class="badge bg-secondary">Video File</span>
                                    @endif
                                </td>
                                <td><span class="badge {{ $item->type == 'photo' ? 'bg-info' : 'bg-warning' }} text-dark">{{ strtoupper($item->type) }}</span></td>
                                <td>
                                    <strong>{{ $item->title ?? 'No Title' }}</strong><br>
                                    <small class="text-muted">{{ $item->category }}</small>
                                </td>
                                <td>
                                    <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $item->id }})" class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>