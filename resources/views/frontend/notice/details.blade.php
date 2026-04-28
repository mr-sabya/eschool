@extends('frontend.layouts.app')

@section('content')
<section class="notice-details-area py-5 bg-light">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <!-- Notice Category & Date -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary px-3 py-2 mb-2">
                                <i class="fas fa-tag me-1"></i> {{ $notice->notice_type ?? 'সাধারণ নোটিশ' }}
                            </span>
                            <span class="text-muted mb-2">
                                <i class="fas fa-calendar-alt me-1"></i>
                                প্রকাশিত তারিখ: {{ $notice->start_date ? $notice->start_date->format('d M, Y') : $notice->created_at->format('d M, Y') }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h1 class="display-6 fw-bold text-dark mb-4 border-bottom pb-3">
                            {{ $notice->title }}
                        </h1>

                        <!-- Description -->
                        <div class="notice-content text-secondary lh-lg mb-5" style="font-size: 1.1rem; text-align: justify;">
                            {!! $notice->description !!}
                        </div>

                        <!-- Attachment Section -->
                        @if($notice->attachment)
                        <div class="attachment-box p-4 bg-light border rounded d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    @php $ext = pathinfo($notice->attachment, PATHINFO_EXTENSION); @endphp
                                    @if($ext == 'pdf')
                                    <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                    @else
                                    <i class="fas fa-file-image fa-3x text-info"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold">সংযুক্ত ফাইল</h6>
                                    <p class="mb-0 small text-muted">ডাউনলোড করে বিস্তারিত দেখুন</p>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <a href="{{ asset('storage/' . $notice->attachment) }}" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-eye me-1"></i> দেখুন
                                </a>
                                <a href="{{ asset('storage/' . $notice->attachment) }}" download class="btn btn-primary btn-sm">
                                    <i class="fas fa-download me-1"></i> ডাউনলোড
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white py-3">
                        <a href="{{ url()->previous() }}" wire:navigate class="btn btn-link text-decoration-none p-0 text-muted">
                            <i class="fas fa-arrow-left me-1"></i> ফিরে যান
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3 mt-4 mt-lg-0">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white fw-bold">
                        জরুরী নোটিশ
                    </div>
                    <ul class="list-group list-group-flush">
                        {{-- This assumes you pass $recentNotices from the controller --}}
                        @isset($recentNotices)
                        @foreach($recentNotices as $recent)
                        <li class="list-group-item small">
                            <a href="{{ route('notice.show', $recent->id) }}" class="text-decoration-none text-dark d-block text-truncate">
                                {{ $recent->title }}
                            </a>
                            <span class="text-muted x-small">{{ $recent->start_date->format('d M, Y') }}</span>
                        </li>
                        @endforeach
                        @else
                        <li class="list-group-item text-muted small">কোন তথ্য পাওয়া যায়নি।</li>
                        @endisset
                    </ul>
                </div>

                <!-- Helpful Links or Contact -->
                <div class="bg-white p-3 rounded shadow-sm border">
                    <h6 class="fw-bold mb-3">যোগাযোগ</h6>
                    <p class="small mb-1"><i class="fas fa-phone me-2 text-primary"></i> {{ $settings->phone ?? '+880 1234 567890' }}</p>
                    <p class="small"><i class="fas fa-envelope me-2 text-primary"></i> {{ $settings->email ?? 'info@school.edu.bd' }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .notice-details-area {
        min-height: 80vh;
    }

    .notice-content p {
        margin-bottom: 1.5rem;
    }

    .x-small {
        font-size: 0.75rem;
    }

    .attachment-box {
        transition: 0.3s;
    }

    .attachment-box:hover {
        background-color: #f1f3f5 !important;
    }
</style>
@endsection