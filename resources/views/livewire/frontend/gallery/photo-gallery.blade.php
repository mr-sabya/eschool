<div class="container my-5">
    <h2 class="text-center mb-4">ফটো গ্যালারি</h2>
    <hr class="mb-5">

    <div class="row g-4">
        @forelse($photos as $photo)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0">
                <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank">
                    <img src="{{ asset('storage/' . $photo->file_path) }}"
                        class="card-img-top rounded"
                        alt="{{ $photo->title }}"
                        style="height: 200px; object-fit: cover;">
                </a>
                @if($photo->title || $photo->category)
                <div class="card-body p-2 text-center">
                    <p class="card-text mb-0 fw-bold small text-truncate">{{ $photo->title }}</p>
                    <span class="badge bg-light text-dark border small">{{ $photo->category }}</span>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-12 d-flex align-items-center justify-content-center" style="min-height: 60vh;">
            <div class="text-center p-5 shadow-sm rounded-4 bg-light border border-dashed" style="max-width: 500px; border: 2px dashed #dee2e6;">

                <!-- প্রফেশনাল ফটো আইকন -->
                <div class="mb-4">
                    <div class="icon-shape bg-white shadow-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="fas fa-images fa-3x text-muted"></i>
                    </div>
                </div>

                <!-- টেক্সট কন্টেন্ট -->
                <h3 class="fw-bold text-dark mb-2">কোনো ছবি পাওয়া যায়নি</h3>
                <p class="text-muted mb-4 px-3">
                    দুঃখিত, বর্তমানে আমাদের ফটো গ্যালারিতে প্রদর্শনের জন্য কোনো ছবি নেই। নতুন ছবি আপলোড করা হলে সেগুলো এখানে স্বয়ংক্রিয়ভাবে চলে আসবে।
                </p>

                <!-- অ্যাকশন বাটনসমূহ -->
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="/" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" wire:navigate>
                        <i class="fas fa-home me-2"></i> হোম পেজে ফিরে যান
                    </a>
                    <button wire:click="$refresh" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                        <i class="fas fa-sync-alt me-2"></i> রিফ্রেশ করুন
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $photos->links() }}
    </div>
</div>