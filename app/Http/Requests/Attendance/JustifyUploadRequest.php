<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class JustifyUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'attendance_id' => 'required|exists:attendances,id',
            'employee_id' => 'required|exists:users,id',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // Limitar tamaño a 10MB
        ];
    }

    public function messages()
    {
        return [
            'files.*.file' => 'Cada archivo debe ser un archivo válido.',
            'files.*.mimes' => 'Los archivos deben ser de tipo: jpg, jpeg, png o pdf.',
            'files.*.max' => 'El tamaño máximo permitido para cada archivo es de 10 MB.',
        ];
    }

    public function authorize(): bool
    {
        return true; // Cambiar lógica si hay permisos específicos.
    }
}
