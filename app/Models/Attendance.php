<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    // Definir los estados como constantes
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';

    // Definir los campos que son asignables en masa
    protected $fillable = [
        'user_id',
        'entry_time',
        'exit_time',
        'status',
    ];

    // Accesor para obtener solo la hora de entrada en formato 'H:i:s'
    private Carbon $entry_time;
    private Carbon $exit_time;

    // Relación con el modelo User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accesor para obtener la hora de entrada en formato 'H:i:s'
    public function getEntryTimeFormattedAttribute(): string
    {
        return $this->entry_time->format('H:i:s');
    }

    // Accesor para obtener la hora de salida en formato 'H:i:s'
    public function getExitTimeFormattedAttribute(): string
    {
        return $this->exit_time->format('H:i:s');
    }

}

