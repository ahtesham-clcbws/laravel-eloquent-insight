<?php

namespace App\Notifications;

use App\Models\Corporate;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnyUserEmailVerify extends Notification
{
    use Queueable;

    public Corporate | Student $user;
    public int $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
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
        return (new MailMessage)
            ->subject('Verify Email')
            ->view('mail.notification.verify-email', [
                'name' => $this->user->name,
                'otp' => $this->otp
            ]);
        // ->greeting('Hello! ' . $this->user->name)
        // ->line('Please verify your email address')
        // ->line("OTP: {$this->otp}")
        // ->line('Thank you!');
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
