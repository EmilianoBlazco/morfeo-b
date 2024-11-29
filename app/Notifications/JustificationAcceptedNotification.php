<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JustificationAcceptedNotification extends Notification
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'justify_id' => $this->justifyUpload->id, // ID del justificativo
            'message' => 'Tu justificativo ha sido aceptado.', // Mensaje para el usuario
            'url' => '/justifications/' . $this->justifyUpload->id, // Enlace para más detalles
            'approved_at' => now(), // Fecha y hora de aprobación
        ];
    }
}
