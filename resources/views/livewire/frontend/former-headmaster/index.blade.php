<div class="container my-5">
    <h2 class="text-center mb-5 fw-bold">প্রাক্তন প্রধান শিক্ষকবৃন্দ</h2>

    @if($headmasters->count() > 0)
    <div class="row g-4 justify-content-center">
        @foreach($headmasters as $hm)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 border-0 shadow-sm text-center p-3 staff-card">
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $hm->image) }}"
                        class="rounded shadow-sm border border-3 border-light"
                        style="width: 140px; height: 160px; object-fit: cover;"
                        alt="{{ $hm->name }}">
                </div>
                <h6 class="fw-bold mb-1 text-dark">{{ $hm->name }}</h6>
                <p class="text-muted small mb-2">{{ $hm->qualification }}</p>

                <div class="mt-auto">
                    <div class="bg-light py-1 px-2 rounded-pill small border">
                        <i class="far fa-calendar-alt me-1"></i>
                        {{ $hm->joining_date?->format('Y') }} - {{ $hm->leaving_date?->format('Y') ?? 'বর্তমান' }}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Professional Empty State -->
    <div class="d-flex align-items-center justify-content-center" style="height: 60vh;">
        <div class="text-center p-5 border border-dashed rounded-4 bg-light shadow-sm" style="max-width: 500px; border: 2px dashed #ccc !important;">
            <i class="fas fa-user-graduate fa-4x text-muted mb-3 opacity-50"></i>
            <h4 class="text-dark fw-bold">কোনো তথ্য পাওয়া যায়নি</h4>
            <p class="text-muted">বর্তমানে আমাদের ডাটাবেসে কোনো প্রাক্তন প্রধান শিক্ষকের তথ্য নেই।</p>
            <a href="/" class="btn btn-primary rounded-pill px-4 mt-2">হোম পেজে যান</a>
        </div>
    </div>
    @endif


    <style>
        .staff-card {
            transition: 0.3s;
            border-radius: 15px;
        }

        .staff-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
</div>