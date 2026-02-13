<div class="container my-5" >
    <h2 class="text-center mb-5 fw-bold">পরিচালনা পর্ষদ</h2>

    @if($members->count() > 0)
    <div class="row g-4 justify-content-center">
        @foreach($members as $member)
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-sm text-center p-3 transition-hover">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('storage/' . $member->image) }}"
                        class="rounded-circle border border-3 border-primary p-1"
                        style="width: 150px; height: 150px; object-fit: cover;"
                        alt="{{ $member->name }}">
                </div>
                <div class="card-body">
                    <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                    <p class="text-primary fw-semibold mb-2">{{ $member->designation }}</p>
                    @if($member->mobile)
                    <p class="small text-muted"><i class="fas fa-phone-alt me-1"></i> {{ $member->mobile }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Professional Empty State -->
    <div class="d-flex align-items-center justify-content-center" style="height: 60vh;">
        <div class="text-center p-5 border border-dashed rounded-4">
            <i class="fas fa-users-slash fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">বর্তমানে কোনো সদস্যের তথ্য পাওয়া যায়নি</h4>
        </div>
    </div>
    @endif

    <style>
        .transition-hover:hover {
            transform: translateY(-5px);
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
        }
    </style>
</div>