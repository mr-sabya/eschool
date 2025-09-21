<div class="container my-5">

    <div class="mb-5">
        <h2 class="mb-2 text-center">Class: {{ $class->name }}</h2>
        <p class="text-center">Total Students: {{ $studentCount }}</p>
    </div>

    <div class="row g-4">
        @forelse($students as $student)
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 teacher-card">
                <div class="card-body text-center">

                    {{-- Profile Image --}}
                    <div class="mb-3">
                        @if($student->profile_picture)
                        <img src="{{ asset('storage/'.$student->profile_picture) }}"
                            class="staff-image"
                            alt="{{ $student->user['name'] }}">
                        @else
                        <img src="{{ $student->user->getUrlfriendlyAvatar($size=400) }}"
                            class="staff-image"
                            alt="{{ $student->user['name'] }}">
                        @endif
                    </div>

                    {{-- Name & Info --}}
                    <h5 class="fw-bold mb-0">
                        {{ $student->user['name'] }}
                    </h5>
                    <p class="text-primary mb-1 small">
                        Roll: {{ $student->roll_number ?? 'N/A' }}
                    </p>
                    <p class="text-muted small mb-2">
                        {{ $student->schoolClass->name ?? 'Class' }}
                        @if($student->classSection)
                        - {{ $student->classSection->name }}
                        @endif
                    </p>
                </div>

            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                No students found in this class.
            </div>
        </div>
        @endforelse
    </div>

    <!-- pagination -->
    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>