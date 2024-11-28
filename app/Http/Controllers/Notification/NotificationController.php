<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getUnreadNotifications(Request $request): JsonResponse
    {
        $userId = $request->query('user_id');
        $response = $this->notificationService->getUnreadNotification($userId);

        return response()->json($response);
    }

    public function markSingleNotificationAsRead(Request $request, $notificationId): JsonResponse
    {
        $userId = $request->user()->id;
        $response = $this->notificationService->markNotificationRead($userId, $notificationId);

        return response()->json($response);
    }

    public function markAllNotificationsAsRead(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $response = $this->notificationService->markAllNotificationsRead($userId);

        return response()->json($response);
    }

    public function notifySupervisorOfAbsence(Request $request): JsonResponse
    {
        $user = $request->user();
        $response = $this->notificationService->notifySupervisorAbsence($user);

        return response()->json($response);
    }
}
