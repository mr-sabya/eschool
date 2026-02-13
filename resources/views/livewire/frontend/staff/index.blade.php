<div class="container my-5" style="min-height: 100vh;">
    <!-- Header & Filters -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-0">কর্মকর্তা ও কর্মচারী বৃন্দ</h2>
            <p class="text-muted">আমাদের প্রতিষ্ঠানের নিবেদিতপ্রাণ সদস্যগণ</p>
        </div>
        <div class="col-md-6">
            <div class="row g-2">
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" wire:model.live="search" class="form-control border-start-0" placeholder="নাম বা আইডি দিয়ে খুঁজুন...">
                    </div>
                </div>
                <div class="col-md-5">
                    <select wire:model.live="selectedDepartment" class="form-select">
                        <option value="">সকল বিভাগ</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Grid -->
    <div class="row g-4">
        @forelse($staffs as $staff)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm staff-card text-center p-4">
                <!-- Profile Picture -->
                <div class="position-relative mb-3">
                    @if($staff->profile_picture)
                    <img src="{{ asset('storage/' . $staff->profile_picture) }}"
                        class="rounded-circle border border-4 border-light shadow-sm"
                        style="width: 120px; height: 120px; object-fit: cover;"
                        alt="{{ $staff->first_name }}">
                    @else
                    <!-- Fallback to UI Avatar if no picture -->
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($staff->first_name) }}&background=random&size=120"
                        class="rounded-circle border border-4 border-light shadow-sm"
                        alt="Avatar">
                    @endif
                </div>

                <!-- Details -->
                <div class="card-body p-0">
                    <h5 class="fw-bold mb-1 text-dark">{{ $staff->first_name }} {{ $staff->last_name }}</h5>
                    <div class="badge bg-primary-subtle text-primary px-3 rounded-pill mb-2">
                        {{ $staff->designation->name ?? 'পদবী নেই' }}
                    </div>
                    <p class="small text-muted mb-3">
                        <i class="fas fa-building me-1"></i> {{ $staff->department->name ?? 'বিভাগ নেই' }}
                    </p>

                    <hr class="my-3 opacity-25">

                    <div class="d-flex justify-content-center gap-3">
                        @if($staff->phone)
                        <a href="tel:{{ $staff->phone }}" class="btn btn-outline-secondary btn-sm rounded-circle" title="কল করুন">
                            <i class="fas fa-phone"></i>
                        </a>
                        @endif
                        @if($staff->email)
                        <a href="mailto:{{ $staff->email }}" class="btn btn-outline-secondary btn-sm rounded-circle" title="ইমেইল">
                            <i class="fas fa-envelope"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Professional Empty State -->
        <div class="col-12 py-5 d-flex align-items-center justify-content-center">
            <div class="text-center p-5 border border-dashed rounded-4 bg-light">
                <i class="fas fa-user-tie fa-4x text-muted mb-3 opacity-50"></i>
                <h4 class="text-muted">কোনো কর্মকর্তা বা কর্মচারী পাওয়া যায়নি</h4>
                <p class="text-muted small">আপনার সার্চ ফিল্টার পরিবর্তন করে পুনরায় চেষ্টা করুন।</p>
                <button wire:click="$set('search', '')" class="btn btn-primary btn-sm mt-2">রিসেট করুন</button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-5">
        {{ $staffs->links() }}
    </div>


    <style>
        .staff-card {
            transition: all 0.3s ease;
            border-radius: 15px;
        }

        .staff-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
            background-color: #ffffff;
        }

        .btn-outline-secondary:hover {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            color: white;
        }

        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
        }
    </style>
</div>