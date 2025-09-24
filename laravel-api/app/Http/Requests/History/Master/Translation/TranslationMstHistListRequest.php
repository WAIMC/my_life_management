<?php

namespace App\Http\Requests\History\Master\Translation;

use Illuminate\Foundation\Http\FormRequest;

class TranslationMstHistListRequest extends FormRequest
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
            'translation_mst_id' => 'nullable|integer',
            'language_id' => 'nullable|integer',
            'original_id' => 'nullable|integer',
            'value' => 'nullable|string',
            'action' => 'nullable|integer',
            'author_id' => 'nullable|integer',
            'created_at' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1',
            'sort_by' => 'nullable|string|in:id,translation_mst_id,language_id,original_id,value,action,author_id,created_at',
            'sort_direction' => 'nullable|string|in:asc,desc'
        ];
    }

    /**
     * Get the attributes for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'translation_mst_id' => 'Translation ID',
            'language_id' => 'Language ID',
            'original_id' => 'Original ID',
            'value' => 'Value',
            'action' => 'Action',
            'author_id' => 'Author ID',
            'created_at' => 'Created At',
            'per_page' => 'Per Page',
            'sort_by' => 'Sort By',
            'sort_direction' => 'Sort Direction'
        ];
    }
}