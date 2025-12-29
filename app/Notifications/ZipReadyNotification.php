<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ZipReadyNotification extends Notification
{
    use Queueable;

    protected $downloadUrl;

    public function __construct($downloadUrl)
    {
        $this->downloadUrl = $downloadUrl;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // We'll store this in the DB to show in the UI
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'zip_ready',
            'title' => 'Your Result ZIP is Ready!',
            'message' => 'The zip file with all student reports has been generated successfully.',
            'download_url' => $this->downloadUrl,
        ];
    }
}
