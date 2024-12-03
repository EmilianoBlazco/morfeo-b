<?php

namespace App\Http\Controllers\License;

use App\Http\Requests\License\StoreLicenseRequest;
use App\Services\License\LicenseRequestService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LicenseRequestController extends Controller
{
    private LicenseRequestService $licenseRequestService;

    public function __construct(LicenseRequestService $licenseRequestService)
    {
        $this->licenseRequestService = $licenseRequestService;
    }

    // Listar todas las solicitudes de licencia
    public function index(Request $request)
    {
        return response()->json($this->licenseRequestService->getAllLicenseRequests($request), 200);
    }

    // Mostrar detalles de una solicitud específica
    public function show($id)
    {
        $licenseRequest = $this->licenseRequestService->getLicenseRequestById($id);

        return response()->json($licenseRequest, 200);
    }

    // Crear una nueva solicitud de licencia
    public function store(StoreLicenseRequest $request)
    {
        $data = $request->validated();
        $data['justification_file'] = $request->file('file_0');
        $licenseRequest = $this->licenseRequestService->createLicenseRequest($data);

        return response()->json($licenseRequest, 201);
    }

    // Eliminar una solicitud de licencia
    public function destroy($id)
    {
        $this->licenseRequestService->deleteLicenseRequest($id);

        return response()->json(['message' => 'License request deleted successfully'], 200);
    }

    //Obtener las solicitudes de licencia asignadas a un supervisor
    public function getSupervisorRequests()
    {
        return response()->json($this->licenseRequestService->getSupervisorRequests(), 200);
    }
}
