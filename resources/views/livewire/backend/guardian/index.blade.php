<div class="card">
    <div class="card-header bg-primary">
        <div class="card-title m-0 text-white">Guardians List</div>
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
                <input type="text" class="form-control form-control-sm" wire:model.debounce.500ms="search" placeholder="Search...">
            </div>
        </div>

        <table class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer">#</th>
                    <th wire:click="sortBy('name')" style="cursor: pointer">Name</th>
                    <th>Phone</th>
                    <th>Occupation</th>
                    <th>Profile</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guardians as $user)
                <tr>
                    <td>{{ ($guardians->currentPage() - 1) * $guardians->perPage() + $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->guardian->phone ?? '-' }}</td>
                    <td>{{ $user->guardian->occupation ?? '-' }}</td>
                    <td>
                        @if($user->guardian->profile_picture)
                        <img src="{{ asset('storage/'.$user->guardian->profile_picture) }}" width="40" class="rounded">
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="{{ route('admin.guardian.edit', $user->id) }}" wire:navigate><i class="ri-edit-line"></i></a>
                        <button class="btn btn-sm btn-danger"
                            wire:click="confirmDelete({{ $user->id }})">
                            Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No guardians found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <small>Showing {{ $guardians->firstItem() }} to {{ $guardians->lastItem() }} of {{ $guardians->total() }} guardians</small>
        {{ $guardians->links() }}
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
                    Are you sure you want to delete this guardian? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDelete', false)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>