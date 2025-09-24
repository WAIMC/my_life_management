<?php

namespace App\Http\Requests\Master\Translation;

use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationMstRequest extends FormRequest
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
            'language_id' => 'required|integer|exists:language_mst,id',
            'original_id' => 'required|integer|exists:original_translator_mst,id',
            'value' => 'required|string|max:255',
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
        ];
    }
}
