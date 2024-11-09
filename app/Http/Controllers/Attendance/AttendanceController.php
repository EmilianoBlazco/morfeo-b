<?php

namespace App\Http\Controllers\Attendance;

use App\Exceptions\Attendance\EntryAlreadyExistsException;
use App\Exceptions\Attendance\ExitWithoutEntryException;
use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use http\Client\Request;
use Illuminate\Http\JsonResponse;

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


/*    public function markEntry(Request $request): JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            $scanTime = $request->input('scan_time');

            $attendance = $this->attendanceService->recordEntry($userId, $scanTime);

            return response()->json(['data' => $attendance, 'message' => 'Entrada registrada correctamente']);
        }catch (EntryAlreadyExistsException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function markExit(Request $request): JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            $scanTime = $request->input('scan_time');

            $attendance = $this->attendanceService->recordExit($userId, $scanTime);

            return response()->json(['data' => $attendance, 'message' => 'Salida registrada correctamente']);
        } catch (ExitWithoutEntryException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }*/
}
