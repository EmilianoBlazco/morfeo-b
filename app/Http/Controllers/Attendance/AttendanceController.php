<?php

namespace App\Http\Controllers\Attendance;

use App\Exceptions\Attendance\EntryAlreadyExistsException;
use App\Exceptions\Attendance\ExitWithoutEntryException;
use App\Http\Controllers\Controller;
use App\Services\Attendance\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function markAttendance(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');
        $scanTime = $request->input('scan_time');

        // Determinar si se registra entrada o salida basado en el atributo 'action'
        if ($request->attributes->get('action') === 'entry') {
            try {
                $attendance = $this->attendanceService->markEntry($userId, $scanTime);
                return response()->json(['message' => 'Entrada registrada.', 'data' => $attendance], 200);
            } catch (EntryAlreadyExistsException $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        } else {
            try {
                $attendance = $this->attendanceService->markExit($userId, $scanTime);
                return response()->json(['message' => 'Salida registrada.', 'data' => $attendance], 200);
            }catch (ExitWithoutEntryException $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
    }

    public function getAttendance(Request $request): JsonResponse
    {
        $userId = $request->input('user_id');

        $attendance = $this->attendanceService->getAttendanceAll($userId);

        return response()->json([$attendance], 200);
    }
}
