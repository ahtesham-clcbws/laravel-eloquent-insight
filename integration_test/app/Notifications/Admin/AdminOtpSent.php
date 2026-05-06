<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminOtpSent extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $otp;
    public $ipAddress;
    public $userAgent;

    public function __construct($otp, $ipAddress, $userAgent)
    {
        $this->otp = $otp;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
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
        $otp = $this->otp;
        $ipAddress = $this->ipAddress;
        $userAgent = $this->userAgent;

        return (new MailMessage)
        ->subject('Success: admin OTP')
        ->markdown('mail.admin.admin_otp',compact('otp','ipAddress','userAgent'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
}
