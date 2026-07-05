<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeRegistrationNotification extends Notification
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
            ->subject('Welcome to Kiyovu Sports')
            ->greeting("Muraho {$this->registrant->first_name},")
            ->line('Your account on the Kiyovu Sports Internal Rules platform has been created.')
            ->line('An administrator will review and activate your account shortly.')
            ->action('Go to Kiyovu Sports Portal', config('app.frontend_url'))
            ->line('Thank you for being part of Kiyovu Sports Association.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Welcome to Kiyovu Sports',
            'body' => 'Your account has been created and is pending activation.',
        ];
    }
}
