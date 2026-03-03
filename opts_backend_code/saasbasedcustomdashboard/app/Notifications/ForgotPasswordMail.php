<?php

namespace App\Notifications;

use App\Enums\UserTypeEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordMail extends Notification
{
    use Queueable;
    private $forgotPasswordURL;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token, UserTypeEnum $userType)
    {
        $appUrl = config('app.url');
        $frontendAppUrl = config('app.frontend_app_url');
        if ($userType === UserTypeEnum::COMPANY) {
            $this->forgotPasswordURL = "{$frontendAppUrl}/reset-password?token={$token}";
        }

        if ($userType === UserTypeEnum::SUPER_ADMIN) {
            $this->forgotPasswordURL = "{$appUrl}/reset-password?token={$token}";
        }
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
        return (new MailMessage)->view('emails.superAdmin.forgot_password', ['url' => $this->forgotPasswordURL]);
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
