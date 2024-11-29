<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'dni',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación con asistencias
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    // Relación con justificativos subidos por este usuario
    public function justifyUploadsAsEmployee()
    {
        return $this->hasMany(JustifyUpload::class, 'employee_id');
    }

    // Relación con justificativos supervisados por este usuario
    public function justifyUploadsAsSupervisor()
    {
        return $this->hasMany(JustifyUpload::class, 'supervisor_id');
    }
}
