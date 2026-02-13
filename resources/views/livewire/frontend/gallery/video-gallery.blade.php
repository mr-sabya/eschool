<div class="container my-5">
    <h2 class="text-center mb-4">ভিডিও গ্যালারি</h2>
    <hr class="mb-5">

    <div class="row g-4">
        @forelse($videos as $video)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="ratio ratio-16x9">
                    <video controls preload="metadata" class="rounded-top">
                        <source src="{{ asset('storage/' . $video->file_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="card-body">
                    <h5 class="card-title h6 mb-1 text-truncate">{{ $video->title ?? 'শিরোনামহীন ভিডিও' }}</h5>
                    @if($video->category)
                    <span class="badge bg-primary-subtle text-primary small">{{ $video->category }}</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 d-flex align-items-center justify-content-center" style="min-height: 60vh;">
            <div class="text-center p-5 shadow-sm rounded-4 bg-light border border-dashed" style="max-width: 500px; border: 2px dashed #dee2e6;">
                <!-- Professional Icon -->
                <div class="mb-4">
                    <div class="icon-shape bg-white shadow-sm rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="fas fa-video-slash fa-3x text-muted"></i>
                    </div>
                </div>

                <!-- Text Content -->
                <h3 class="fw-bold text-dark mb-2">কোনো ভিডিও পাওয়া যায়নি</h3>
                <p class="text-muted mb-4 px-3">
                    দুঃখিত, বর্তমানে আমাদের গ্যালারিতে প্রদর্শনের জন্য কোনো ভিডিও নেই। নতুন ভিডিও আপলোড করা হলে এখানে দেখতে পাবেন।
                </p>

                <!-- Action Button -->
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
        {{ $videos->links() }}
    </div>
</div>