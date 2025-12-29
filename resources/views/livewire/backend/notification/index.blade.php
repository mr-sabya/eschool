<div wire:poll.10s="checkForUpdates"> {{-- Auto updates every 10 seconds --}}
    <div class="card">
        <div class="card-header bg-light border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title m-0">All Notifications</h5>
                    <p class="text-muted mb-0 small">Manage and review all your notifications.</p>
                </div>
                <div class="btn-group" role="group">
                    <button wire:click="markAllAsRead" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-check-double me-1"></i> Mark All as Read
                    </button>
                    <button wire:click="deleteAll" wire:confirm="Are you sure you want to delete ALL notifications?" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete All
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- "New Notification" Alert -->
            @if($showNewNotificationAlert)
            <div class="alert alert-info rounded-0 border-0 mb-0 d-flex justify-content-between align-items-center" role="alert">
                <div><i class="fas fa-sync fa-spin me-2"></i> You have new notifications.</div>
                <button wire:click="refreshNotifications" type="button" class="btn btn-sm btn-info">Update List</button>
            </div>
            @endif

            <ul class="list-group list-group-flush">
                @forelse($notifications as $notification)
                @php
                $data = $notification->data;
                $isZipProgress = ($data['type'] ?? '') === 'zip_progress';

                // Icon Logic
                $icon = 'fa-bell';
                $iconColor = 'bg-primary';
                if (isset($data['download_url'])) { $icon = 'fa-file-archive'; $iconColor = 'bg-success'; }
                elseif ($isZipProgress) { $icon = 'fa-spinner fa-spin'; $iconColor = 'bg-info'; }
                @endphp

                <li class="list-group-item p-3 {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                    <div class="d-flex align-items-start">
                        <!-- Icon -->
                        <div class="me-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white {{ $iconColor }}" style="width: 40px; height: 40px;">
                                <i class="fas {{ $icon }}"></i>
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="flex-grow-1">
                            <h6 class="mb-1 {{ is_null($notification->read_at) ? 'fw-bold' : '' }}">
                                {{ $data['title'] ?? 'Notification' }}
                            </h6>
                            <p class="mb-1 text-muted">{{ $data['message'] }}</p>

                            <!-- ZIP PROGRESS BAR -->
                            @if($isZipProgress && isset($data['percentage']))
                            <div class="mt-2" style="max-width: 300px;">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                        role="progressbar"
                                        style="width: {{ $data['percentage'] }}%"></div>
                                </div>
                                <small class="text-info fw-bold">{{ $data['percentage'] }}% processed...</small>
                            </div>
                            @endif

                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>

                        <!-- Actions -->
                        <div class="ms-auto text-nowrap">
                            @if(isset($data['download_url']))
                            <a href="{{ $data['download_url'] }}" class="btn btn-sm btn-success" wire:click="markAsRead('{{ $notification->id }}')">
                                <i class="fas fa-download me-1"></i> Download ZIP
                            </a>
                            @endif

                            <button wire:click="delete('{{ $notification->id }}')" class="btn btn-sm btn-link text-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </li>
                @empty
                <div class="text-center p-5 text-muted">
                    <i class="fas fa-bell-slash fa-3x mb-3"></i>
                    <p>No notifications found.</p>
                </div>
                @endforelse
            </ul>
        </div>

        @if($notifications->hasPages())
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>