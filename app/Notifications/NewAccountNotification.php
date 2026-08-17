<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAccountNotification extends Notification
{
    public function __construct(private readonly string $temporaryPassword)
    {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Account Has Been Created')
            ->line('An administrator has created an account for you.')
            ->line("Temporary password: {$this->temporaryPassword}")
            ->line('You must change this password the first time you log in.')
            ->line('If you were not expecting this account, please contact an administrator.');
    }
}
