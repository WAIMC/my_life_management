<?php

namespace App\Http\Requests\Master\Translation;

use Illuminate\Foundation\Http\FormRequest;

class TranslationMstListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'language_id' => 'nullable|integer',
            'original_id' => 'nullable|integer',
            'value' => 'nullable|string',
            'with_language' => 'nullable|boolean',
            'with_original' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'language_id' => 'Language ID',
            'original_id' => 'Original Translator ID',
            'value' => 'Translation value',
            'with_language' => 'Include language details',
            'with_original' => 'Include original translator details',
        ];
    }
}
