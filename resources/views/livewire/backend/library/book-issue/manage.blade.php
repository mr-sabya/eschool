<div class="row">
    <div class="col-lg-8">
        <form wire:submit.prevent="save">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <div class="card-title m-0 text-white">{{ $bookIssueId ? 'Edit' : 'Add' }} Book Issue</div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <!-- Book -->
                        <div class="col-md-6 mb-3">
                            <label>Book <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="book_id">
                                <option value="">Select Book</option>
                                @foreach($books as $book)
                                <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                            @error('book_id')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Member Search -->
                        <div class="col-md-6 mb-3">
                            <label>Member ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="member_search" placeholder="Search Member ID...">
                            <ul class="list-group mt-1" style="position: absolute; z-index: 10;" wire:if="$members">
                                @foreach($members as $member)
                                <li class="list-group-item list-group-item-action" wire:click="selectMember({{ $member->id }})">
                                    {{ $member->member_id }} ({{ $member->user?->name }})
                                </li>
                                @endforeach
                            </ul>
                            @error('member_id')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Dates -->
                        <div class="col-md-6 mb-3">
                            <label>Issue Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="issue_date">
                            @error('issue_date')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="due_date">
                            @error('due_date')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Return Date</label>
                            <input type="date" class="form-control" wire:model="return_date">
                            @error('return_date')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Fine -->
                        <div class="col-md-6 mb-3">
                            <label>Fine Amount</label>
                            <input type="number" class="form-control" wire:model="fine_amount">
                            @error('fine_amount')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>



                    </div>
                    <!-- Status -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" wire:model="status" id="isActive">
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ $bookIssueId ? 'Update' : 'Save' }}</button>
                    <button type="button" wire:click="resetForm" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>