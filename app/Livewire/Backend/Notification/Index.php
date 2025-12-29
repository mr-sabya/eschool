<?php

namespace App\Livewire\Backend\Notification;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $showNewNotificationAlert = false;
    public $lastCount;

    // Listeners for manual triggers
    protected $listeners = ['new-notification' => 'showNewNotificationAlert'];

    public function mount()
    {
        // Store initial count to detect changes
        $this->lastCount = Auth::user()->notifications()->count();
    }

    /**
     * This function runs every 10 seconds via wire:poll
     */
    public function checkForUpdates()
    {
        $currentCount = Auth::user()->notifications()->count();

        // If count increased, show the "Refresh" alert
        if ($currentCount > $this->lastCount) {
            $this->showNewNotificationAlert = true;
            $this->lastCount = $currentCount;
        }
    }

    public function showNewNotificationAlert()
    {
        $this->showNewNotificationAlert = true;
    }

    public function refreshNotifications()
    {
        $this->showNewNotificationAlert = false;
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function delete($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
            $this->lastCount = Auth::user()->notifications()->count();
        }
    }

    public function deleteAll()
    {
        Auth::user()->notifications()->delete();
        $this->lastCount = 0;
    }

    public function render()
    {
        return view('livewire.backend.notification.index', [
            'notifications' => Auth::user()
                ->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate(15),
        ]);
    }
}
