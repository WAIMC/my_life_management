<?php

namespace App\Http\Requests\History\Master\Translation;

use Illuminate\Foundation\Http\FormRequest;

class DeleteTranslationMstHistRequest extends FormRequest
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
            'id' => 'required|integer|exists:translation_mst_hist,id'
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
            'id' => 'ID'
        ];
    }
}