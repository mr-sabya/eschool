<div>
    <div class="card">
        <div class="card-header bg-primary">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 text-white">All Notifications</h5>
                <div class="btn-group" role="group">
                    <button wire:click="markAllAsRead" class="btn btn-sm btn-light">Mark All as Read</button>
                    <button wire:click="deleteAll" wire:confirm="Are you sure you want to delete all notifications? This cannot be undone." class="btn btn-sm btn-danger">Delete All</button>
                </div>
            </div>
        </div>

        <div class="card-body">

            <!-- "New Notification" Alert -->
            @if($showNewNotificationAlert)
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                You have new notifications.
                <button wire:click="refreshNotifications" type="button" class="btn-sm btn-outline-info ms-3">Refresh</button>
                <button type="button" class="btn-close" wire:click="$set('showNewNotificationAlert', false)" aria-label="Close"></button>
            </div>
            @endif

            <!-- List of Notifications -->
            @forelse($notifications as $notification)
            <div class="d-flex align-items-start p-3 border-bottom {{ is_null($notification->read_at) ? 'bg-light fw-bold' : '' }}">

                <div class="flex-grow-1 ms-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="{{ is_null($notification->read_at) ? '' : 'text-muted' }}">{{ $notification->data['title'] }}</h6>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>

                    <p class="mb-1 {{ is_null($notification->read_at) ? 'text-dark' : 'text-muted' }}">{{ $notification->data['message'] }}</p>
                    <!-- download_url -->
                    @if(isset($notification->data['download_url']))
                    <a href="{{ $notification->data['download_url'] }}" class="btn btn-sm btn-outline-success">Download</a>
                    @endif

                    <div class="mt-2">
                        @if($notification->link)
                        <a href="{{ $notification->link }}" class="btn btn-sm btn-outline-primary" wire:click.prevent="markAsRead({{ $notification->id }})">View Details</a>
                        @endif

                        @if(is_null($notification->read_at))
                        <button wire:click="markAsRead({{ $notification->id }})" class="btn btn-sm btn-secondary">Mark as Read</button>
                        @endif
                        <button wire:click="delete({{ $notification->id }})" class="btn btn-sm btn-outline-danger">Delete</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center p-5">
                <p class="text-muted">You don't have any notifications yet.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>