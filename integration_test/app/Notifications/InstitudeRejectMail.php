<?php

namespace App\Notifications;

use App\Models\Corporate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstitudeRejectMail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $corporate;

    public function __construct($corporate)
    {
        $this->corporate = $corporate;
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
    { $corporate = $this->corporate;
        return (new MailMessage)
        ->subject('Reply: Reject')
        ->markdown('mail.institude.rejectNotification',compact('corporate'));
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
