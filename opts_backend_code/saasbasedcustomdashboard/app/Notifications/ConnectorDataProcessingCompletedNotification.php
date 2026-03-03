<?php

namespace App\Notifications;

use App\Enums\ConnectorEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ConnectorDataProcessingCompletedNotification extends Notification
{
    use Queueable;
    private $connectorType;
    private $frontendAppUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($connectorType)
    {
        $this->connectorType = $connectorType;
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
        $view = null;
        $subject = "Data Processing Completed";
        if ($this->connectorType === ConnectorEnum::QUICKBOOKS) {
            $subject = $subject . " for " . Str::ucfirst(ConnectorEnum::QUICKBOOKS->value);
            $view = 'emails.quickbooks.data_processing_completed';
        }

        if ($this->connectorType === ConnectorEnum::GOOGLESHEET) {
            $subject = $subject . " for " . Str::ucfirst(ConnectorEnum::GOOGLESHEET->value);
            $view = 'emails.googleSheet.data_processing_completed';
        }

        if ($this->connectorType === ConnectorEnum::SALESFORCE) {
            $subject = $subject . " for " . Str::ucfirst(ConnectorEnum::SALESFORCE->value);
            $view = 'emails.salesForce.data_processing_completed';
        }

        return (new MailMessage)->subject($subject)
            ->view($view, [
                "name" => $notifiable->name,
                "app_url" => $this->frontendAppUrl
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
