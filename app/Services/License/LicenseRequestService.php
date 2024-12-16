<?php

namespace App\Services\License;

use App\Models\License;
use App\Models\LicenseRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LicenseRequestService
{
    // Obtener todas las solicitudes de licencia
    public function getAllLicenseRequests($filters)
    {
        return LicenseRequest::with(['license', 'employee', 'supervisor'])
            ->filter($filters) // Puedes agregar un scope en el modelo para filtros avanzados
            ->get();
    }

    // Obtener una solicitud específica por ID
    public function getLicenseRequestById($id)
    {
        return LicenseRequest::with(['license', 'employee', 'supervisor'])
            ->findOrFail($id);
    }

    // Crear una nueva solicitud de licencia
    public function createLicenseRequest(array $data)
    {
        $license = License::findOrFail($data['license_id']);

        $data['employee_id'] = Auth::id(); // Asigna el empleado logueado
        $data['supervisor_id'] = $this->assignSupervisor()->id;
        $data['start_date'] = Carbon::parse($data['start_date']); // Convertir a instancia de Carbon
        $data['end_date'] = $data['start_date']->copy()->addDays($license->max_duration_days);
        $data['justification_file'] = $data['justification_file']->store('license_requests');

        return LicenseRequest::create($data);
    }

    // Eliminar una solicitud de licencia
    public function deleteLicenseRequest($id)
    {
        $licenseRequest = LicenseRequest::findOrFail($id);

        // Validar si está permitido eliminar
        if ($licenseRequest->status !== 'Pendiente') {
            throw new \Exception('Only pending requests can be deleted.');
        }

        $licenseRequest->delete();
    }

    private function assignSupervisor(): ?User
    {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2); // Rol de Supervisor (ID = 2)
        })->first();
    }

    // Obtener las solicitudes de licencia asignadas a un supervisor
    public function getSupervisorRequests()
    {
        $supervisorId = Auth::id();

        return LicenseRequest::with(['license', 'employee'])
            ->where('supervisor_id', $supervisorId)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'license_name' => $request->license->name ?? null,
                    'employee_name' => $request->employee->name . ' ' . $request->employee->surname,
                    'start_date' => Carbon::parse($request->start_date)->toDateString(),
                    'end_date' => Carbon::parse($request->end_date)->toDateString(),
                    'created_at' => $request->created_at->toDateTimeString(),
                    'duration_days' => $request->duration_days,
                    'status' => $request->status,
                    'justification_file' => $request->justification_file
                        ? asset('storage/' . $request->justification_file)
                        : null, // Generar la URL pública del archivo si existe
                ];
            });
    }
}
