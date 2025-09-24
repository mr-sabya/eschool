<div>
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
                    <button wire:click="deleteAll" wire:confirm="Are you sure you want to delete ALL notifications? This cannot be undone." class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete All
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">

            <!-- "New Notification" Alert -->
            @if($showNewNotificationAlert)
            <div class="alert alert-info rounded-0 border-start-0 border-end-0 mb-0 d-flex justify-content-between align-items-center" role="alert">
                <div><i class="fas fa-info-circle me-2"></i> You have new notifications.</div>
                <button wire:click="refreshNotifications" type="button" class="btn btn-sm btn-info">Refresh Now</button>
            </div>
            @endif

            <!-- List of Notifications using a List Group for better spacing and separation -->
            <ul class="list-group list-group-flush">
                @forelse($notifications as $notification)
                {{--
                        To make this even more dynamic, you could add a 'type' column
                        (e.g., 'info', 'success', 'warning', 'danger') to your notifications table.
                        Then you can change the icon and color based on the type.
                    --}}
                @php
                // Default icon and color
                $icon = 'fa-bell';
                $iconColor = 'bg-primary';

                // Example of dynamic icons based on notification data
                if (isset($notification->data['download_url'])) {
                $icon = 'fa-download';
                $iconColor = 'bg-success';
                } elseif (isset($notification->data['type'])) {
                switch($notification->data['type']) {
                case 'success': $icon = 'fa-check-circle'; $iconColor = 'bg-success'; break;
                case 'warning': $icon = 'fa-exclamation-triangle'; $iconColor = 'bg-warning'; break;
                case 'danger': $icon = 'fa-exclamation-circle'; $iconColor = 'bg-danger'; break;
                default: $icon = 'fa-info-circle'; $iconColor = 'bg-info'; break;
                }
                }
                @endphp

                <li class="list-group-item p-3">
                    <div class="d-flex align-items-start">
                        <!-- Unread Indicator Dot -->
                        <div class="me-3">
                            @if(is_null($notification->read_at))
                            <span class="badge bg-primary rounded-pill p-1" title="Unread">
                                <span class="visually-hidden">Unread</span>
                            </span>
                            @else
                            <span class="badge bg-light rounded-pill p-1"></span>
                            @endif
                        </div>

                        <!-- Icon -->
                        <div class="me-3">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white {{ $iconColor }}" style="width: 40px; height: 40px;">
                                <i class="fas {{ $icon }}"></i>
                            </span>
                        </div>

                        <!-- Main Content -->
                        <div class="flex-grow-1">
                            <h6 class="mb-1 {{ is_null($notification->read_at) ? 'fw-bold' : '' }}">{{ $notification->data['title'] }}</h6>
                            <p class="mb-1 text-muted">{{ $notification->data['message'] }}</p>
                            <small class="text-muted">{{ $notification->created_at->format('M d, Y \a\t h:i A') }} ({{ $notification->created_at->diffForHumans() }})</small>
                        </div>

                        <!-- Actions -->
                        <div class="ms-auto text-nowrap">
                            @if(isset($notification->data['download_url']))
                            <a href="{{ $notification->data['download_url'] }}" class="btn btn-sm btn-primary">Download</a>
                            @elseif($notification->link)
                            <a href="{{ $notification->link }}" class="btn btn-sm btn-primary" wire:click.prevent="markAsRead({{ $notification->id }})">View</a>
                            @endif

                            <!-- Ellipsis Dropdown for secondary actions -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(is_null($notification->read_at))
                                    <li><a class="dropdown-item" href="#" wire:click.prevent="markAsRead({{ $notification->id }})">
                                            <i class="fas fa-check-circle me-2"></i>Mark as Read
                                        </a></li>
                                    @endif
                                    <li><a class="dropdown-item text-danger" href="#" wire:click.prevent="delete({{ $notification->id }})">
                                            <i class="fas fa-trash-alt me-2"></i>Delete
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <!-- Professional Empty State -->
                <div class="text-center p-5">
                    <div class="mb-3">
                        <i class="fas fa-bell-slash fa-4x text-muted"></i>
                    </div>
                    <h4>No Notifications Found</h4>
                    <p class="text-muted">It looks like your notification inbox is empty.</p>
                </div>
                @endforelse
            </ul>
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="card-footer bg-light">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>