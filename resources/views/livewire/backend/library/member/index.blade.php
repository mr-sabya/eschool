<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            Library Member List
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6 d-flex gap-2 align-items-center">
                    <select wire:model="perPage" class="form-select form-select-sm w-auto">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>Records per page</span>
                </div>

                <div class="col-md-6">
                    <input type="text" wire:model.debounce.500ms="search" class="form-control form-control-sm"
                        placeholder="Search by user, category, or member ID...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th wire:click="sortBy('id')" style="cursor:pointer;">
                                # @if($sortField === 'id')
                                <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('member_id')" style="cursor:pointer;">
                                Member ID @if($sortField === 'member_id')
                                <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i>
                                @endif
                            </th>
                            <th>User</th>
                            <th>Category</th>
                            <th>Join Date</th>
                            <th>Expire Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($members as $member)
                        <tr>
                            <td>{{ ($members->currentPage() - 1) * $members->perPage() + $loop->iteration }}</td>
                            <td>{{ $member->member_id }}</td>
                            <td>{{ $member->user?->name }}</td>
                            <td>{{ $member->category?->name }}</td>
                            <td>{{ $member->join_date }}</td>
                            <td>{{ $member->expire_date ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary"
                                    wire:click="$emitTo('backend.library-member.manage','edit',{{ $member->id }})">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="btn btn-sm btn-danger"
                                    wire:click="confirmDelete({{ $member->id }})"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No members found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $members->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this member? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="deleteConfirmed" class="btn btn-danger" data-bs-dismiss="modal">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>