<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JustificationRejectedNotification extends Notification
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
        // Incluimos el canal de base de datos
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'justify_id' => $this->justifyUpload->id, // ID del justificativo
            'message' => 'Tu justificativo ha sido rechazado.',
            'url' => '/justifications/' . $this->justifyUpload->id, // URL para ver detalles
            'supervisor' => $this->justifyUpload->supervisor->name ?? null, // Información adicional, si aplica
            'rejected_at' => now(), // Marca de tiempo del rechazo
        ];
    }

    /**
     * Get the array representation of the notification (opcional para otros canales).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'justify_id' => $this->justifyUpload->id,
            'message' => 'Tu justificativo ha sido rechazado.',
            'url' => '/justifications/' . $this->justifyUpload->id,
        ];
    }
}
