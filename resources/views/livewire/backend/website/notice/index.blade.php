<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="card-title m-0 text-white">Notice List</div>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
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

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th wire:click="sortBy('id')" style="cursor:pointer">
                                # @if($sortField==='id') <i class="{{ $sortDirection==='asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                            </th>
                            <th wire:click="sortBy('title')" style="cursor:pointer">
                                Title @if($sortField==='title') <i class="{{ $sortDirection==='asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                            </th>
                            <th>Type</th>
                            <th>Attachment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($notices as $notice)
                        <tr>
                            <td>{{ ($notices->currentPage() - 1) * $notices->perPage() + $loop->iteration }}</td>
                            <td>{{ $notice->title }}</td>
                            <td>{{ ucfirst($notice->notice_type) }}</td>
                            <td>
                                @if($notice->attachment)
                                <a href="{{ asset('storage/' . $notice->attachment) }}" target="_blank">View</a>
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $notice->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <a href="{{ route('admin.website.notice.edit', $notice->id) }}" class="btn btn-sm btn-primary" wire:navigate><i class="ri-edit-line"></i></a>
                                <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $notice->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="ri-delete-bin-line"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No notices found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $notices->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this notice? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed" data-bs-dismiss="modal">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>