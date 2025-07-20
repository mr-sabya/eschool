<div class="card">
    <div class="card-header bg-primary">
        <div class="card-title m-0 text-white">Staff List</div>
    </div>

    <div class="card-body">
        <div class="table-action d-flex justify-content-between mb-3">
            <div class="d-flex gap-2 align-items-center">
                <select wire:model="perPage" class="form-select form-select-sm w-auto">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>Records per page</span>
            </div>
            <div class="w-25">
                <input type="text" wire:model.debounce.500ms="search" class="form-control form-control-sm" placeholder="Search...">
            </div>
        </div>

        <table class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor:pointer">#</th>
                    <th wire:click="sortBy('name')" style="cursor:pointer">Name</th>
                    <th>Email</th>
                    <th>Designation</th>
                    <th>Profile</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staffUsers as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->staff->designation->name ?? '-' }}</td>
                    <td>
                        @if($user->staff && $user->staff->profile_picture)
                        <img src="{{ asset('storage/' . $user->staff->profile_picture) }}" width="40" class="rounded" alt="Profile">
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.staff.edit', $user->id) }}" wire:navigate class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $user->id }})">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No staff found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <small>Showing {{ $staffUsers->firstItem() }} to {{ $staffUsers->lastItem() }} of {{ $staffUsers->total() }} staff</small>
        {{ $staffUsers->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade @if($confirmingDelete) show d-block @endif" tabindex="-1" style="@if($confirmingDelete) display:block; background:rgba(0,0,0,0.5); @endif">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" wire:click="$set('confirmingDelete', false)"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this staff? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>