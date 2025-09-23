<?php

namespace App\Http\Requests\Master\Language;

use Illuminate\Foundation\Http\FormRequest;

class LanguageMstUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'abbreviation' => 'sometimes|required|string|max:10',
            'name' => 'sometimes|required|string|max:30',
            'is_active' => 'sometimes|required|boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'abbreviation' => 'Language abbreviation',
            'name' => 'Language name',
            'is_active' => 'Active status',
        ];
    }
}
