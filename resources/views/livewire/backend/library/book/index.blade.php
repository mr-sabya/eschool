<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            Book List
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
                    <input type="text" wire:model.debounce.500ms="search" class="form-control form-control-sm" placeholder="Search by title, author or ISBN...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th wire:click="sortBy('id')" style="cursor:pointer;">
                                # @if($sortField === 'id') <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                            </th>
                            <th wire:click="sortBy('title')" style="cursor:pointer;">
                                Title @if($sortField === 'title') <i class="{{ $sortDirection === 'asc' ? 'ri-arrow-up-s-fill' : 'ri-arrow-down-s-fill' }}"></i> @endif
                            </th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>ISBN</th>
                            <th>Quantity</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($books as $book)
                        <tr>
                            <td>{{ ($books->currentPage() - 1) * $books->perPage() + $loop->iteration }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->category?->name }}</td>
                            <td>{{ $book->isbn }}</td>
                            <td>{{ $book->quantity }}</td>
                            <td>{{ $book->available_quantity }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" wire:click="$emitTo('backend.book.manage','edit',{{ $book->id }})"><i class="ri-edit-line"></i></button>
                                <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $book->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="ri-delete-bin-line"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No books found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $books->links() }}
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
                    Are you sure you want to delete this book? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="deleteConfirmed" class="btn btn-danger" data-bs-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>