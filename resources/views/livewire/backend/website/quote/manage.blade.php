<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">{{ $quote_id ? 'Edit' : 'Add' }} Quote</div>
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="mb-2"><label>Name</label><input type="text" wire:model="name" class="form-control"></div>
                    <div class="mb-2"><label>Designation</label><input type="text" wire:model="designation" class="form-control"></div>
                    <div class="mb-2"><label>Institution</label><input type="text" wire:model="institution" class="form-control"></div>
                    <div class="mb-2"><label>Location</label><input type="text" wire:model="location" class="form-control"></div>
                    <div class="mb-2"><label>Message</label><textarea wire:model="message" class="form-control" rows="4"></textarea></div>
                    <div class="mb-2">
                        <label>Photo (400x500)</label>
                        @if($new_image) <img src="{{ $new_image->temporaryUrl() }}" width="50" class="d-block mb-1">
                        @elseif($image) <img src="{{ asset('storage/'.$image) }}" width="50" class="d-block mb-1"> @endif
                        <input type="file" wire:model="new_image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Save Quote</button>
                    <button type="button" wire:click="resetForm" class="btn btn-link mt-2">Reset</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quotes as $q)
                        <tr>
                            <td>
                                @if($q->image)
                                <img src="{{ asset('storage/'.$q->image) }}" width="40" class="rounded border">
                                @else
                                <span class="text-muted small">No Image</span>
                                @endif
                            </td>
                            <td>{{ $q->name }}</td>
                            <td>{{ $q->designation }}</td>
                            <td>
                                <button wire:click="edit({{ $q->id }})" class="btn btn-sm btn-info text-white">
                                    <i class="fa fa-edit"></i> Edit
                                </button>

                                <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $q->id }})"
                                    class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i> Del
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No quotes found. Click "Add Quote" to create one.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>