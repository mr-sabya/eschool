<div class="card">
    <div class="card-header bg-primary">
        <div class="card-title m-0 text-white">Banner List</div>
    </div>

    <div class="card-body">

        <div class="table-action d-flex justify-content-between mb-3">
            <div class="d-flex gap-2 align-items-center">
                <select wire:model="perPage" class="form-select form-select-sm w-auto">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>Records per page</span>
            </div>
            <div class="w-25">
                <input type="text" class="form-control form-control-sm"
                    wire:model.debounce.500ms="search"
                    placeholder="Search...">
            </div>
        </div>

        <table class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer">#</th>
                    <th wire:click="sortBy('title')" style="cursor: pointer">Title</th>
                    <th>Text</th>
                    <th>Image</th>
                    <th wire:click="sortBy('order')" style="cursor: pointer">Order</th>
                    <th wire:click="sortBy('is_active')" style="cursor: pointer">Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td>{{ $banner->id }}</td>
                    <td>{{ $banner->title ?? '-' }}</td>
                    <td>{{ $banner->text ?? '-' }}</td>
                    <td>
                        @if($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}" width="60" class="rounded">
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>{{ $banner->order }}</td>
                    <td>
                        @if($banner->is_active)
                        <span class="badge bg-success">Yes</span>
                        @else
                        <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-sm btn-primary"
                            href="{{ route('admin.website.banner.edit', $banner->id) }}"
                            wire:navigate>
                            Edit
                        </a>
                        <button class="btn btn-sm btn-danger"
                            wire:click="confirmDelete({{ $banner->id }})">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No banners found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <small>
            Showing {{ $banners->firstItem() }} to {{ $banners->lastItem() }} of {{ $banners->total() }} banners
        </small>
        {{ $banners->links() }}
    </div>

    <!-- ✅ Delete Confirmation Modal -->
    <div class="modal fade @if($confirmingDelete) show d-block @endif" tabindex="-1"
        style="@if($confirmingDelete) display:block; background:rgba(0,0,0,0.5); @endif">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" wire:click="$set('confirmingDelete', false)"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this banner? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>