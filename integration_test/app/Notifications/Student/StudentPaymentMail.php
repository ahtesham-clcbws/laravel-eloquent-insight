<?php

namespace App\Notifications\Student;

use App\Models\student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentPaymentMail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $studentCode;
    public $student;

    public function __construct($studentCode)
    {
        if(is_null($studentCode)){
            return;
        }
        
        $this->studentCode = $studentCode;
        $this->student = $studentCode->student;
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
        $student = $this->student;
        $studentCode = $this->studentCode;
        return (new MailMessage)
        ->subject('Success: Student Payment Done:')
        ->markdown('mail.student.student_payment',compact('student','studentCode'));
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
