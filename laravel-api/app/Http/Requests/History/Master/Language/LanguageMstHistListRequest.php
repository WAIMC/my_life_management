<?php

namespace App\Http\Requests\History\Master\Language;

use Illuminate\Foundation\Http\FormRequest;

class LanguageMstHistListRequest extends FormRequest
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
            'id' => 'nullable|integer',
            'language_mst_id' => 'nullable|integer',
            'abbreviation' => 'nullable|string|max:10',
            'name' => 'nullable|string|max:30',
            'is_active' => 'nullable|boolean',
            'action' => 'nullable|integer',
            'author_id' => 'nullable|integer',
            'created_at' => 'nullable|date',
            'sort_by' => 'nullable|string|in:id,language_mst_id,abbreviation,name,is_active,action,author_id,created_at',
            'sort_direction' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100'
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
            'id' => __('messages.id'),
            'language_mst_id' => __('messages.language_mst_id'),
            'abbreviation' => __('messages.abbreviation'),
            'name' => __('messages.name'),
            'is_active' => __('messages.is_active'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id'),
            'created_at' => __('messages.created_at'),
            'sort_by' => __('messages.sort_by'),
            'sort_direction' => __('messages.sort_direction'),
            'per_page' => __('messages.per_page')
        ];
    }
}
