<?php

namespace App\Services\Attendance;

use App\Exceptions\Attendance\EntryAlreadyExistsException;
use App\Exceptions\Attendance\ExitWithoutEntryException;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AttendanceService
{
    /**
     * @throws EntryAlreadyExistsException
     */
    public function recordEntry($userId, $scanTime)
    {
        // Verificar si el usuario ya registró una entrada en el día
        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereDate('entry_time', Carbon::parse($scanTime)->toDateString())
            ->first();

        if ($existingAttendance) {
            throw new EntryAlreadyExistsException();
        }

        // Crear nuevo registro de entrada
        $attendance = new Attendance();
        $attendance->user_id = $userId;
        $attendance->entry_time = Carbon::parse($scanTime);

        // Lógica para definir el estado basado en el tiempo de entrada
        $entryMinute = Carbon::parse($scanTime)->diffInMinutes(Carbon::parse($scanTime)->startOfDay()->addHours(8));
        if ($entryMinute <= 10) {
            $attendance->status = Attendance::STATUS_PRESENT;
        } elseif ($entryMinute <= 15) {
            $attendance->status = Attendance::STATUS_LATE;
        } else {
            $attendance->status = Attendance::STATUS_ABSENT;
        }

        $attendance->save();
        return $attendance;
    }

    /**
     * @throws ExitWithoutEntryException
     */
    public function recordExit($userId, $scanTime)
    {
        // Verificar si el usuario tiene una entrada para hoy sin salida
        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereDate('entry_time', Carbon::parse($scanTime)->toDateString())
            ->whereNull('exit_time')
            ->first();

        if (!$existingAttendance) {
            throw new ExitWithoutEntryException();
        }

        // Registrar la hora de salida
        $existingAttendance->exit_time = Carbon::parse($scanTime);
        $existingAttendance->save();

        return $existingAttendance;
    }

    /**
     * Obtener todas las inasistencias para un usuario específico (opcional).
     *
     * @param int|null $userId
     * @return Collection
     */
    public function getAttendanceAll(int $userId = null): Collection
    {
        $query = Attendance::query();

        // Si se proporciona un user_id, agregarlo al filtro
        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
