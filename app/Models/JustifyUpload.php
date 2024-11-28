<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JustifyUpload extends Model
{
    use HasFactory;

    protected $table = 'justify_uploads';

    // Definir los estados como constantes
    const STATUS_ACCEPT = 'Aceptado';
    const STATUS_REJECTED = 'Rechazado';
    const STATUS_PENDING = 'Pendiente';

    protected $fillable = [
        'employee_id',
        'supervisor_id',
        'attendance_id',
        'file_path',
        'status',
    ];

    // Relación con el empleado (user)
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Relación con el supervisor (user)
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // Relación con la asistencia asociada
    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }
}
