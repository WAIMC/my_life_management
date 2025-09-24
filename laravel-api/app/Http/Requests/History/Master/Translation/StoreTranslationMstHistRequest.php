<?php

namespace App\Http\Requests\History\Master\Translation;

use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationMstHistRequest extends FormRequest
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
            'id' => 'required|integer',
            'translation_mst_id' => 'required|integer|exists:translation_mst,id',
            'language_id' => 'nullable|integer|exists:language_mst,id',
            'original_id' => 'nullable|integer|exists:original_translator_mst,id',
            'value' => 'nullable|string|max:255',
            'action' => 'required|integer',
            'author_id' => 'required|integer',
            'created_at' => 'required|string'
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
            'created_at' => 'Created At'
        ];
    }
}