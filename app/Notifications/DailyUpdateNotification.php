<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DailyUpdateNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $updateTitle,
        public string $reporterName,
        public string $projectName,
        public ?string $url = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'category'   => 'updates',
            'title'      => 'New Daily Update Log',
            'message'    => "{$this->reporterName} submitted a daily progress update for '{$this->projectName}'.",
            'type'       => 'info',
            'url'        => $this->url ?? route('daily-updates.index'),
            'reporter'   => $this->reporterName,
        ];
    }
}
