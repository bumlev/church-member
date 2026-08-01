<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public function __construct(private readonly string $token)
    {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Reset Your Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line("Reset token: {$this->token}")
            ->line('Submit this token together with your email and new password to the password reset endpoint.');

        if ($frontendUrl = config('app.frontend_url')) {
            $message->action('Reset Password', sprintf(
                '%s/reset-password?token=%s&email=%s',
                rtrim($frontendUrl, '/'),
                $this->token,
                urlencode($notifiable->getEmailForPasswordReset())
            ));
        }

        return $message
            ->line('This password reset token will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
