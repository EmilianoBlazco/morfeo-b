<?php

namespace App\Http\Requests\License;

use Illuminate\Foundation\Http\FormRequest;

class StoreLicenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cambiar según la lógica de autorización
    }

    public function rules(): array
    {
        return [
            'license_id' => 'required|exists:licenses,id',
            'start_date' => 'required|date|after_or_equal:today',
            //'justification_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}
