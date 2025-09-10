<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            Book Issue List
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
                    <input type="text" wire:model.debounce.500ms="search" class="form-control form-control-sm" placeholder="Search by Book, Member ID, or Issued By">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th wire:click="sortBy('id')" style="cursor:pointer;">
                                # @if($sortField === 'id') <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                            </th>
                            <th wire:click="sortBy('book_id')" style="cursor:pointer;">
                                Book @if($sortField === 'book_id') <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                            </th>
                            <th>Member ID</th>
                            <th>Issued By</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Fine</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookIssues as $issue)
                        <tr>
                            <td>{{ ($bookIssues->currentPage() - 1) * $bookIssues->perPage() + $loop->iteration }}</td>
                            <td>{{ $issue->book?->title }}</td>
                            <td>{{ $issue->member?->member_id }}</td>
                            <td>{{ $issue->issuedBy?->name }}</td>
                            <td>{{ $issue->issue_date }}</td>
                            <td>{{ $issue->due_date }}</td>
                            <td>{{ $issue->return_date ?? '-' }}</td>
                            <td>{{ $issue->fine_amount ?? 0 }}</td>
                            <td>{{ $issue->status }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" wire:click="$emitTo('backend.library.book-issue.manage','edit',{{ $issue->id }})"><i class="ri-edit-line"></i></button>
                                <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $issue->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="ri-delete-bin-line"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No book issues found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $bookIssues->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this book issue?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="deleteConfirmed" class="btn btn-danger" data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>