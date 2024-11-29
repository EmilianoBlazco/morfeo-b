<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function markAttendance(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $scanTime = $request->input('scan_time');

        try {
            $attendance = $this->attendanceService->markAttendance($userId, $scanTime);
            $message = $attendance->exit_time ? 'Salida registrada.' : 'Entrada registrada.';
            return response()->json(['message' => $message, 'data' => $attendance]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }
}
