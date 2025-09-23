<?php

namespace App\Http\Requests\History\Master\Language;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageMstHistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'language_mst_id' => 'nullable|integer|exists:language_mst,id',
            'abbreviation' => 'nullable|nullable|string|max:10',
            'name' => 'nullable|nullable|string|max:30',
            'is_active' => 'nullable|boolean',
            'action' => 'nullable|integer',
            'author_id' => 'nullable|integer|exists:admin_mst,id'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'language_mst_id' => __('messages.language_mst_id'),
            'abbreviation' => __('messages.abbreviation'),
            'name' => __('messages.name'),
            'is_active' => __('messages.is_active'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id')
        ];
    }
}
