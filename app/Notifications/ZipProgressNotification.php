<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ZipProgressNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $percentage,
        public string $message
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type'       => 'zip_progress',
            'title'      => 'ZIP Generation',
            'percentage' => $this->percentage,
            'message'    => $this->message,
        ]);
    }
}
