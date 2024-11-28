<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JustifyAssignedNotification extends Notification
{
    use Queueable;

    private $justifyUpload;

    /**
     * Create a new notification instance.
     */
    public function __construct($justifyUpload)
    {
        $this->justifyUpload = $justifyUpload;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Se te ha asignado una justificación para verificar.',
            /*'employee_name' => $this->justifyUpload->employee->name,
            'attendance_date' => $this->justifyUpload->attendance->date,
            'file_path' => $this->justifyUpload->file_path,*/
        ];
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
