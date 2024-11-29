<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\JustifyUploadRequest;
use App\Services\Attendance\JustifyUploadService;
use Illuminate\Http\JsonResponse;

class JustifyUploadController extends Controller
{
    protected JustifyUploadService $justifyUploadService;

    public function __construct(JustifyUploadService $justifyUploadService)
    {
        $this->justifyUploadService = $justifyUploadService;
    }

    public function notifySupervisor(JustifyUploadRequest $request): JsonResponse
    {
        $this->justifyUploadService->handleUpload($request->validated());
        return response()->json(['message' => 'Comenzará el proceso de evaluación de su justificativo.'], 200);
    }

    public function getJustificationsSupervisor(): JsonResponse
    {
        $supervisorId = request()->query('user_id');

        if (!$supervisorId) {
            return response()->json(['error' => 'El ID del supervisor es requerido.'], 400);
        }

        $justifications = $this->justifyUploadService->getJustifications($supervisorId);

        return response()->json([
            'data' => $justifications,
        ], 200);
    }

    public function acceptJustification($id): JsonResponse
    {
        try {
            $this->justifyUploadService->acceptJustification($id);

            return response()->json(['message' => 'Justificativo aceptado con éxito.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al aceptar justificativo.'], 500);
        }
    }

    public function rejectJustification($id): JsonResponse
    {
        try {
            $this->justifyUploadService->rejectJustification($id);

            return response()->json(['message' => 'Justificativo rechazado con éxito.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al rechazar justificativo.'], 500);
        }
    }


}
