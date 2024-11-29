<?php


namespace App\Services\Notification;

use App\Models\User;
use App\Notifications\EmployeeAbsentNotification;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    // Obtener notificaciones no leídas
    public function getUnreadNotification(int $userId): array
    {
        $unreadNotifications = DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->get();

        return [
            'count' => $unreadNotifications->count(),
            'notifications' => $unreadNotifications,
        ];
    }

    // Marcar una notificación individual como leída
    public function markNotificationRead(int $userId, string $notificationId): array
    {
        $updated = DB::table('notifications')
            ->where('id', $notificationId)
            ->where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $unreadNotifications = $this->getUnreadNotification($userId);

        return [
            'status' => $updated ? 'success' : 'error',
            'message' => $updated ? null : 'Notification not found or already read',
            'count' => $unreadNotifications['count'],
            'notifications' => $unreadNotifications['notifications'],
        ];
    }

    // Marcar todas las notificaciones del usuario como leídas
    public function markAllNotificationsRead(int $userId): array
    {
        $updated = DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return [
            'status' => $updated ? 'success' : 'error',
            'message' => $updated ? null : 'No unread notifications found',
        ];
    }

    // Notificar ausencia al supervisor
    public function notifySupervisorAbsence(User $user): array
    {
        $supervisor = $this->assignSupervisor();

        if (!$supervisor) {
            return [
                'status' => 'error',
                'message' => 'No active supervisors found',
            ];
        }

        $supervisor->notify(new EmployeeAbsentNotification($user));

        return [
            'status' => 'success',
            'message' => 'Notification sent to supervisor',
        ];
    }

    // Asignar el primer supervisor disponible
    private function assignSupervisor(): ?User
    {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2);
        })->first();
    }
}
