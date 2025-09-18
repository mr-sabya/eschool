<?php

namespace App\Livewire\Backend\Notification;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Bell extends Component
{

    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();
        if ($user) {
            /** @var \App\Models\User $user */ // This line tells Intelephense the real type of $user
            $user->refresh();
            $this->notifications = $user->unreadNotifications;
            $this->unreadCount = $user->unreadNotifications->count();
        }
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        if ($user) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
            }
        }
        // After marking as read, reload the list to update the UI
        $this->loadNotifications();
    }


    public function render()
    {
        return view('livewire.backend.notification.bell', [
            'notifications' => $this->notifications,
            'unreadCount' => $this->unreadCount,
        ]);
    }
}
