<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = rtrim(config('app.frontend_url'), '/').'/reset-password?token='.$this->token.'&email='.urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Reset your Kiyovu Sports password')
            ->greeting("Muraho {$notifiable->first_name},")
            ->line('A password reset was requested for your account.')
            ->action('Reset Password', $url)
            ->line('This link expires in 60 minutes. If you did not request this, no action is needed.');
    }
}
