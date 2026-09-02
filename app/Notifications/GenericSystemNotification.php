<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GenericSystemNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public ?string $actionUrl = null,
        public ?string $actionText = 'View Details',
        public string $type = 'info',
        public ?string $icon = 'bell',
        public ?string $category = 'system',
        public ?string $action = 'system_notice'
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("🔔 {$this->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line($this->message);

        if ($this->actionUrl) {
            $mail->action($this->actionText ?? 'View in GS NexusPM', $this->actionUrl);
        }

        return $mail->line('Thank you for using George Steuart NexusPM.');
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'category'    => $this->category ?? 'system',
            'action'      => $this->action ?? 'system_notice',
            'type'        => $this->type,
            'title'       => $this->title,
            'message'     => $this->message,
            'url'         => $this->actionUrl,
            'action_url'  => $this->actionUrl,
            'action_text' => $this->actionText,
            'icon'        => $this->icon,
        ];
    }
}
