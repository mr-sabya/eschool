<div class="card">

    <div class="card-header bg-primary">
        <div class="card-title m-0 text-white">Final Mark Configurations</div>
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
                <input type="text" class="form-control form-control-sm" wire:model.debounce.500ms="search" placeholder="Search by Class or Subject...">
            </div>
        </div>

        <table class="table table-bordered table-hover table-striped mb-0">
            <thead>
                <tr>
                    <th wire:click="sortBy('id')" style="cursor: pointer">#</th>
                    <th wire:click="sortBy('school_class_id')" style="cursor: pointer">Class</th>
                    <th wire:click="sortBy('subject_id')" style="cursor: pointer">Subject</th>
                    <th wire:click="sortBy('class_test_total')" style="cursor: pointer">Class Test Total</th>
                    <th wire:click="sortBy('other_parts_total')" style="cursor: pointer">Other Parts Total</th>
                    <th wire:click="sortBy('final_result_weight_percentage')" style="cursor: pointer">Final Result Weight (%)</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($configs as $config)
                <tr>
                    <td>{{ $config->id }}</td>
                    <td>{{ $config->schoolClass->name ?? '-' }}</td>
                    <td>{{ $config->subject->name ?? '-' }}</td>
                    <td>{{ $config->class_test_total }}</td>
                    <td>{{ $config->other_parts_total }}</td>
                    <td>{{ $config->final_result_weight_percentage }}</td>
                    <td>
                        <a href="{{ route('admin.final-mark-configuration.edit', $config->id) }}" wire:navigate class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $config->id }})">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <small>
            Showing {{ $configs->firstItem() ?? 0 }} to {{ $configs->lastItem() ?? 0 }} of {{ $configs->total() }} entries
        </small>
        {{ $configs->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade @if($confirmingDeleteId) show d-block @endif" tabindex="-1" style="@if($confirmingDeleteId) display:block; background:rgba(0,0,0,0.5); @endif" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" wire:click="$set('confirmingDeleteId', null)"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this entry? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('confirmingDeleteId', null)">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>