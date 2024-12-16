<?php

namespace App\Models;

use App\Observers\LicenseRequestObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([LicenseRequestObserver::class])]
class LicenseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_id',
        'employee_id',
        'supervisor_id',
        'start_date',
        'end_date',
        'status',
        'justification_file',
    ];

    // Relación con la licencia solicitada
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    // Relación con el empleado (user)
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Relación con el supervisor (user)
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
