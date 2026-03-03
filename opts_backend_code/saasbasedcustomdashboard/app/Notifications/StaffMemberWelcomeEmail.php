<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffMemberWelcomeEmail extends Notification
{
    use Queueable;
    private $password;
    private $frontendAppUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($password)
    {
        $this->password = $password;
        $this->frontendAppUrl = config('app.frontend_app_url');
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
        return (new MailMessage)->subject('Welcome Email')
            ->view('emails.staff-welcome-email', [
                'url' => $this->frontendAppUrl,
                'password' => $this->password,
                "user" => $notifiable
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
