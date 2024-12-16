<?php

namespace App\Http\Requests\License;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LicenseStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'min_duration_days' => 'required|integer|min:0',
            'max_duration_days' => 'required|integer|min:0',
            'annual_limit' => 'nullable|integer|min:0',
            'advance_notice_days' => 'nullable|integer|min:0',
            'requires_justification' => 'boolean',
            'required_documents' => 'nullable|string',
            'is_paid' => 'boolean',
        ];
    }
}
