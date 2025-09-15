<div class="container my-5">

    <h2 class="mb-4 text-center">Our Teachers</h2>

    <div class="row g-4">
        @forelse($teachers as $teacher)
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 teacher-card">
                <div class="card-body text-center">
                    {{-- Profile Image --}}
                    <div class="mb-3">
                        @if($teacher->profile_picture)
                        <img src="{{ asset('storage/'.$teacher->profile_picture) }}"
                            class="staff-image"
                            alt="{{ $teacher->first_name }} {{ $teacher->last_name }}">
                        @else
                        <img src="{{ $teacher->user->getUrlfriendlyAvatar($size=400) }}"
                            class="staff-image"
                            alt="{{ $teacher->first_name }} {{ $teacher->last_name }}">
                        @endif
                    </div>

                    {{-- Name & Info --}}
                    <h5 class="fw-bold mb-0">
                        {{ $teacher->first_name }} {{ $teacher->last_name }}
                    </h5>
                    <p class="text-primary mb-1 small">
                        {{ $teacher->designation->name ?? 'Teacher' }}
                    </p>
                    <p class="text-muted small mb-2">
                        {{ $teacher->department->name ?? '' }}
                    </p>
                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white border-0 text-center pb-3">
                    <a href="mailto:{{ $teacher->email }}"
                        class="btn btn-outline-primary btn-sm rounded-pill me-1"
                        data-bs-toggle="tooltip" title="Send Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <a href="tel:{{ $teacher->phone }}"
                        class="btn btn-outline-success btn-sm rounded-pill"
                        data-bs-toggle="tooltip" title="Call Now">
                        <i class="fas fa-phone"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                No teachers found.
            </div>
        </div>
        @endforelse
    </div>

    <!-- pagination -->
    <div class="mt-4">
        {{ $teachers->links() }}
    </div>
</div>