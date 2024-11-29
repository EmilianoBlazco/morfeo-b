<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeAbsentNotification extends Notification
{
    use Queueable;

    private $user;

    /**
     * Crear una nueva instancia de notificación.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Los canales de entrega de la notificación.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Representación de la notificación por correo.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Notificación de Ausencia de Empleado')
            ->greeting('Hola')
            ->line('Estimado Supervisor,')
            ->line($this->user->name . ' ha informado que no estará presente el día de hoy.')
            ->line('Por favor, tome las medidas necesarias para gestionar la ausencia.')
            ->line('Si tiene alguna pregunta, no dude en ponerse en contacto con Recursos Humanos.')
            ->salutation('Atentamente, El equipo de gestión');
    }

    /**
     * Representación de la notificación en la base de datos.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'message' => $this->user->name . ' ha informado que faltará el día de hoy. Por favor, revise su correo electrónico para más detalles.',
            'alert_type' => 'warning', // Tipo de alerta: warning
            'url' => '/employees/' . $this->user->id,
        ];
    }
}
