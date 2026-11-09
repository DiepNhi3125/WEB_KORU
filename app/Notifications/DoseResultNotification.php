<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class DoseResultNotification extends Notification
{
    public function __construct(
        private readonly array $data
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->data;
    }
}
