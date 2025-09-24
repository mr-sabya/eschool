<?php

namespace App\Livewire\Backend\Notification;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $showNewNotificationAlert = false;

    /**
     * Listen for a global event to show an alert on the page.
     */
    protected $listeners = ['new-notification' => 'showNewNotificationAlert'];

    /**
     * Shows a non-intrusive alert at the top of the page.
     */
    public function showNewNotificationAlert()
    {
        $this->showNewNotificationAlert = true;
    }

    /**
     * Reloads the notifications when the user clicks the alert.
     */
    public function refreshNotifications()
    {
        $this->showNewNotificationAlert = false;
        $this->resetPage(); // Go back to the first page to see the new notification
    }

    /**
     * Marks a single notification as read.
     */
    public function markAsRead(int $notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->update(['read_at' => now()]);
        }
    }

    /**
     * Marks all unread notifications for the user as read.
     */
    public function markAllAsRead()
    {
        if (Auth::check()) {
            Auth::user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        }
    }

    /**
     * Deletes a single notification.
     */
    public function delete(int $notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->delete();
        }
    }

    /**
     * Deletes all of the user's notifications.
     */
    public function deleteAll()
    {
        if (Auth::check()) {
            Auth::user()->notifications()->delete();
        }
    }

    public function render()
    {
        $notifications = Auth::user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15); // Paginate with 15 notifications per page

        return view('livewire.backend.notification.index', [
            'notifications' => $notifications,
        ]);
    }
}
