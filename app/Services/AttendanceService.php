<?php

namespace App\Services;

use App\Exceptions\Attendance\ExitWithoutEntryException;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Exceptions\Attendance\EntryAlreadyExistsException;

class AttendanceService
{
    const MAX_ENTRIES = 2;
    const MAX_EXITS = 2;

    // Horarios de entrada y salida
    const MORNING_START = '08:00';
    const MORNING_END = '12:00';
    const AFTERNOON_START = '19:00';
    const AFTERNOON_END = '20:00';

    /**
     * Marca la asistencia de un usuario.
     *
     * @param int $userId
     * @param string $scanTime
     * @return Attendance
     * @throws EntryAlreadyExistsException
     * @throws ExitWithoutEntryException
     */
    public function markAttendance(int $userId, string $scanTime): Attendance
    {
        $parsedScanTime = Carbon::createFromFormat('d/m/Y, H:i:s', $scanTime);

        // Verificar cuántas entradas y salidas ya existen hoy
        $todayEntries = Attendance::where('user_id', $userId)
            ->whereDate('entry_time', $parsedScanTime->toDateString())
            ->count();

        $todayExits = Attendance::where('user_id', $userId)
            ->whereDate('exit_time', $parsedScanTime->toDateString())
            ->count();

        // Verificar si ya existe una entrada con salida antes del final del turno matutino
        $morningEnd = Carbon::parse($parsedScanTime->toDateString() . ' ' . self::MORNING_END);
        $morningEntryWithExit = Attendance::where('user_id', $userId)
            ->whereDate('entry_time', $parsedScanTime->toDateString())
            ->where('entry_time', '<', $morningEnd)
            ->whereNotNull('exit_time')
            ->exists();

        // Lanzar error si ya se alcanzó el máximo de entradas o salidas permitidas
        if ($todayEntries >= self::MAX_ENTRIES && !$this->isExitTimePending($userId, $parsedScanTime)) {
            throw new EntryAlreadyExistsException("Límite de entradas alcanzado para hoy.");
        }
        if ($todayExits >= self::MAX_EXITS) {
            throw new ExitWithoutEntryException;
        }

        if ($morningEntryWithExit && $parsedScanTime->lessThan($morningEnd)) {
            throw new EntryAlreadyExistsException;
        }

        // Determinar si es entrada o salida
        $existingAttendance = $this->isExitTimePending($userId, $parsedScanTime);

        if (!$existingAttendance) {
            return $this->handleEntry($userId, $parsedScanTime);
        } else {
            return $this->handleExit($existingAttendance, $parsedScanTime);
        }
    }

    private function handleEntry($userId, Carbon $scanTime): Attendance
    {
        // Definir horarios de entrada y salida
        $morningStart = Carbon::parse($scanTime->toDateString() . ' ' . self::MORNING_START);
        $morningEnd = Carbon::parse($scanTime->toDateString() . ' ' . self::MORNING_END);

        $afternoonStart = Carbon::parse($scanTime->toDateString() . ' ' . self::AFTERNOON_START);
        $afternoonEnd = Carbon::parse($scanTime->toDateString() . ' ' . self::AFTERNOON_END);


        // Verificar si la entrada se está marcando después de que terminó el primer turno
        if ($scanTime->greaterThan($morningEnd)) {
            // Si no hay registro de entrada en la mañana, marcar el primer turno como ausente
            $morningAttendance = Attendance::where('user_id', $userId)
                ->whereDate('entry_time', $scanTime->toDateString())
                ->where('entry_time', '<', $morningEnd)
                ->first();

            if (!$morningAttendance) {
                $this->markAbsent($userId, $morningEnd);
            }
        }

        // Crear un nuevo registro de entrada
        $attendance = new Attendance();
        $attendance->user_id = $userId;
        $attendance->entry_time = $scanTime;

        // Determinar el estado (presente, tarde o ausente)
        if ($scanTime->lessThan($morningStart)) {
            $attendance->status = Attendance::STATUS_PRESENT;
        } elseif ($scanTime->between($morningStart, $morningEnd)) {
            $minutesLate = $morningStart->diffInMinutes($scanTime);
            $attendance->status = $minutesLate <= 10
                ? Attendance::STATUS_PRESENT
                : ($minutesLate <= 15 ? Attendance::STATUS_LATE : Attendance::STATUS_ABSENT);
        } elseif ($scanTime->between($morningEnd, $afternoonStart)) {
            $attendance->status = Attendance::STATUS_PRESENT;
        } elseif ($scanTime->between($afternoonStart, $afternoonEnd)) {
            $minutesLate = $afternoonStart->diffInMinutes($scanTime);
            $attendance->status = $minutesLate <= 10
                ? Attendance::STATUS_PRESENT
                : ($minutesLate <= 15 ? Attendance::STATUS_LATE : Attendance::STATUS_ABSENT);
        } else {
            $attendance->status = Attendance::STATUS_ABSENT;
        }

        $attendance->save();
        return $attendance;
    }

    private function markAbsent(int $userId, Carbon $time): void
    {
        $attendance = new Attendance();
        $attendance->user_id = $userId;
        $attendance->entry_time = $time;
        $attendance->exit_time = $time;
        $attendance->status = Attendance::STATUS_ABSENT;
        $attendance->save();
    }

    private function handleExit(Attendance $attendance, Carbon $scanTime): Attendance
    {
        $attendance->exit_time = $scanTime;
        $attendance->save();
        return $attendance;
    }

    private function isExitTimePending($userId, Carbon $scanTime): ?Attendance
    {
        // Verificar si hay una entrada registrada hoy sin salida
        return Attendance::where('user_id', $userId)
            ->whereDate('entry_time', $scanTime->toDateString())
            ->whereNull('exit_time')
            ->first();
    }
}
