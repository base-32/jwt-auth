<?php

namespace CarterParker\JWTAuth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $token;

    /**
     * ResetPasswordNotification constructor.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())->subject('Reset your password')
            ->greeting('Hi ' . $notifiable->full_name)
            ->line('It seems you have sent a request to reset your password, if this was you click on the button below to choose a new one.')
            ->action('Reset Password', config('app.url') . config('jwt-auth.prefix') . '/reset-password?token=' . $this->token . '&email=' . $notifiable->email)
            ->line('If you didn\'t mean to reset your password or didn\'t request a reset, then you can safely ignore this email; your password will remain the same.');
    }

    /**
     * Get the notification delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
}
