<?php

namespace App\Http\Requests\Master\Language;

use Illuminate\Foundation\Http\FormRequest;

class LanguageMstListRequest extends FormRequest
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
            'abbreviation' => 'nullable|string|max:10',
            'name' => 'nullable|string|max:30',
            'is_active' => 'nullable|boolean',
            'sort_by' => 'nullable|string|in:id,abbreviation,name,is_active,created_at,updated_at',
            'sort_order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
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
            'sort_by' => 'Sort field',
            'sort_order' => 'Sort order',
            'per_page' => 'Items per page',
        ];
    }
}
