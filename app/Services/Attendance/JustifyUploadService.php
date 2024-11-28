<?php

namespace App\Services\Attendance;

use App\Models\Attendance;
use App\Models\JustifyUpload;
use App\Models\User;
use App\Notifications\JustificationAcceptedNotification;
use App\Notifications\JustificationRejectedNotification;
use App\Notifications\JustifyAssignedNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class JustifyUploadService
{
    public function handleUpload(array $data): void
    {
        // 1. Guardar archivos
        $filePaths = [];
        foreach ($data['files'] as $file) {
            //$filePaths[] = Storage::put('justify_uploads', $file);
            $filePaths[] = Storage::disk('public')->put('justify_uploads', $file);
        }

        // 2. Crear registro de justificativo
        $justifyUpload = JustifyUpload::create([
            'employee_id' => $data['employee_id'],
            'attendance_id' => $data['attendance_id'],
            'file_path' => count($filePaths) === 1 ? $filePaths[0] : null, // Para un solo archivo
            'status' => JustifyUpload::STATUS_PENDING,
        ]);

        // 4. Asignar supervisor disponible
        $supervisor = $this->assignSupervisor();
        if ($supervisor) {
            $justifyUpload->update(['supervisor_id' => $supervisor->id]);
            // 5. Notificar al supervisor
            $supervisor->notify(new JustifyAssignedNotification($justifyUpload));
        }
    }

    private function assignSupervisor(): ?User
    {
        return User::whereHas('roles', function ($query) {
            $query->where('id', 2); // Rol de Supervisor (ID = 2)
        })->first();
    }

    public function getJustifications(int $supervisorId): Collection
    {
        return JustifyUpload::with(['employee', 'attendance'])
            ->where('supervisor_id', $supervisorId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($justification) {
                return [
                    'id' => $justification->id,
                    'employee_name' => $justification->employee->name . ' ' . $justification->employee->surname,
                    'attendance_date' => $justification->attendance
                        ? $justification->attendance->created_at->toDateString()
                        : null,
                    'status' => $justification->status,
                    'assigned_at' => $justification->created_at->toDateString(),
                    'file_url' => asset('storage/' . $justification->file_path), // Generar URL pública
                ];
            });
    }

    public function acceptJustification(int $id): void
    {
        // Obtener el justificativo
        $justifyUpload = JustifyUpload::findOrFail($id);

        // Cambiar estado del justificativo a "Aceptado" (Proposito)
        $justifyUpload->status = JustifyUpload::STATUS_ACCEPT;
        $justifyUpload->save();

        // Cambiar estado de la asistencia asociada a "Justificado"
        $attendance = $justifyUpload->attendance;
        if ($attendance) {
            $attendance->status = Attendance::STATUS_JUSTIFIED;
            $attendance->save();
        }

        // Notificar al empleado
        $justifyUpload->employee->notify(new JustificationAcceptedNotification($justifyUpload));
    }

    public function rejectJustification(int $id): void
    {
        $justifyUpload = JustifyUpload::findOrFail($id);

        if ($justifyUpload->status !== JustifyUpload::STATUS_PENDING) {
            throw new \Exception('El justificativo ya ha sido procesado.');
        }

        $justifyUpload->status = JustifyUpload::STATUS_REJECTED;
        $justifyUpload->save();

        // Notificar al empleado
        $justifyUpload->employee->notify(new JustificationRejectedNotification($justifyUpload));
    }

}
