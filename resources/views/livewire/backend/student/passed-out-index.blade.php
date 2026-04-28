<div class="card shadow-sm">
    <div class="card-header bg-dark py-3">
        <h5 class="card-title m-0 text-white"><i class="fas fa-user-graduate me-2"></i>Graduated (Passed Out) Students</h5>
    </div>

    <div class="card-body">
        <!-- Filters Row -->
        <div class="row g-2 mb-4 border-bottom pb-4">
            <div class="col-md-3">
                <label class="small fw-bold">Graduated From Session</label>
                <select wire:model.live="filter_session_id" class="form-select form-select-sm">
                    <option value="">All Sessions</option>
                    @foreach($sessions as $session)
                    <option value="{{ $session->id }}">{{ $session->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="small fw-bold">Graduated From Class</label>
                <select wire:model.live="filter_class_id" class="form-select form-select-sm">
                    <option value="">All Classes</option>
                    @foreach($allClasses as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="small fw-bold">Search</label>
                <input type="text" class="form-control form-control-sm" wire:model.live.debounce.300ms="search" placeholder="Search by name, roll, phone...">
            </div>

            <div class="col-md-2 align-self-end">
                <button class="btn btn-sm btn-secondary w-100" wire:click="$set('search', ''); $set('filter_class_id', null); $set('filter_session_id', null);">Reset</button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th wire:click="sortBy('id')" style="cursor:pointer;">ID</th>
                        <th wire:click="sortBy('name')" style="cursor:pointer;">Name</th>
                        <th>Last Class</th>
                        <th>Last Session</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $user)
                    <tr wire:key="passed-out-{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="fw-bold">{{ $user->name }}</div>
                            <small class="text-muted">Roll: {{ $user->student->roll_number ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $user->student->schoolClass->name ?? 'N/A' }}</td>
                        <td>{{ $user->student->academicSession->name ?? 'N/A' }}</td>
                        <td class="text-center">
                            <button wire:click="restoreStudent({{ $user->id }})"
                                wire:confirm="Are you sure you want to restore this student to the active list?"
                                class="btn btn-sm btn-outline-success">
                                <i class="fas fa-undo me-1"></i> Restore
                            </button>
                            <a href="{{ route('admin.student.edit', $user->student->id) }}" class="btn btn-sm btn-outline-primary" wire:navigate>
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No graduated students found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $students->links() }}
        </div>
    </div>
</div>