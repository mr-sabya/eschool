<div class="dropdown d-inline-block" wire:poll.5s="loadNotifications">
    <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-notification-3-line"></i>
        @if($unreadCount > 0)
        <span class="noti-dot"></span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
        aria-labelledby="page-header-notifications-dropdown">
        <div class="p-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0"> Notifications </h6>
                </div>
                <div class="col-auto">
                    <a href="#!" class="small"> View All</a>
                </div>
            </div>
        </div>
        <div data-simplebar style="max-height: 230px;">
            @forelse ($notifications as $notification)
            <a href="javascript:void(0);" wire:click="markAsRead('{{ $notification->id }}')" class="text-reset notification-item">
                <div class="d-flex">
                    @if (isset($notification->data['user_avatar']))
                    <img src="{{ url($notification->data['user_avatar']) }}"
                        class="me-3 rounded-circle avatar-xs" alt="user-pic">
                    @else
                    <div class="avatar-xs me-3">
                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                            <i class="ri-shopping-cart-line"></i>
                        </span>
                    </div>
                    @endif
                    <div class="flex-1">
                        <h6 class="mb-1">{{ $notification->data['title'] }}</h6>
                        <div class="font-size-12 text-muted">
                            <p class="mb-1">{{ $notification->data['message'] }}</p>
                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="p-3 text-center">
                <p class="mb-0">No new notifications</p>
            </div>
            @endforelse
        </div>
        <div class="p-2 border-top">
            <div class="d-grid">
                <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                    <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                </a>
            </div>
        </div>
    </div>
</div>