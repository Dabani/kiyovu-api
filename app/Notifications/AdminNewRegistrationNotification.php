<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNewRegistrationNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly User $registrant) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Registration Pending Review')
            ->greeting('New registration received')
            ->line("{$this->registrant->full_name} ({$this->registrant->email}) has just registered.")
            ->action('Review Registration', config('app.frontend_url').'/admin/users/'.$this->registrant->id)
            ->line('Please review and assign an appropriate role.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New registration pending review',
            'body' => "{$this->registrant->full_name} registered and needs role assignment.",
            'registrant_id' => $this->registrant->id,
        ];
    }
}
