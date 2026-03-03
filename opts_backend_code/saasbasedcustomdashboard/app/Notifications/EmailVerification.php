<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerification extends Notification
{
    use Queueable;
    private $verifyEmailUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $frontendUrl = config('app.frontend_app_url');
        $this->verifyEmailUrl = "{$frontendUrl}/confirm-email?confirmation_token={$token}";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->view('emails.verify-email', [
            'url' => $this->verifyEmailUrl,
            'user_name' => $notifiable?->name
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
