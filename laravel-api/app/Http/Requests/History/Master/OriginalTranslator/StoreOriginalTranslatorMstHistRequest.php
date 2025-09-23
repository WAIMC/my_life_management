<?php

namespace App\Http\Requests\History\Master\OriginalTranslator;

class StoreOriginalTranslatorMstHistRequest extends OriginalTranslatorMstHistRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'original_translator_mst_id' => 'required|integer|exists:original_translator_mst,id',
            'table' => 'nullable|string|max:64',
            'column' => 'nullable|string|max:64',
            'field_id' => 'nullable|integer',
            'action' => 'required|integer|in:1,2,3',
            'author_id' => 'required|integer|exists:admin_mst,id',
        ];
    }
}
