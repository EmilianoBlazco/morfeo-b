<?php

use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Attendance\JustifyUploadController;
use App\Http\Controllers\License\LicenseController;
use App\Http\Controllers\License\LicenseRequestController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Middleware\EnsureHasEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->get('/role', function (Request $request) {
    return $request->user()->getRoleNames();
});

Route::get('/attendances', [AttendanceController::class, 'getAttendance']);
Route::post('/justify-upload', [JustifyUploadController::class, 'notifySupervisor']);
Route::get('/verify-justifications', [JustifyUploadController::class, 'getJustificationsSupervisor']);
Route::patch('/attendance/justify-uploads/accept/{id}', [JustifyUploadController::class, 'acceptJustification']);
Route::patch('/attendance/justify-uploads/reject/{id}', [JustifyUploadController::class, 'rejectJustification']);
Route::middleware(['auth:sanctum'])->get('/notifications', [NotificationController::class, 'getUnreadNotifications']);
Route::patch('/notifications/mark-read/{notificationId}', [NotificationController::class, 'markSingleNotificationAsRead']); // Marca una notificación específica como leída
Route::middleware(['auth:sanctum'])->patch('/notifications/mark-read-all', [NotificationController::class, 'markAllNotificationsAsRead']); // Marca todas las notificaciones como leídas
Route::post('/notifications/notify-absence', [NotificationController::class, 'notifySupervisorOfAbsence']);

Route::middleware([EnsureHasEntry::class])->post('/attendance', [AttendanceController::class, 'markAttendance']);

//Licenses
Route::apiResource('licenses', LicenseController::class)->except(['update']);
Route::get('/license-requests/verify', [LicenseRequestController::class, 'getSupervisorRequests']);
Route::apiResource('license-requests', LicenseRequestController::class)->except(['update']);



require __DIR__.'/auth.php';
