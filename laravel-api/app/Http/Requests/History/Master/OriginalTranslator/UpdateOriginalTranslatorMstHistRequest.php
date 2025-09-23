<?php

namespace App\Http\Requests\History\Master\OriginalTranslator;

class UpdateOriginalTranslatorMstHistRequest extends OriginalTranslatorMstHistRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'original_translator_mst_id' => 'sometimes|integer|exists:original_translator_mst,id',
            'table' => 'sometimes|nullable|string|max:64',
            'column' => 'sometimes|nullable|string|max:64',
            'field_id' => 'sometimes|nullable|integer',
            'action' => 'sometimes|integer|in:1,2,3',
            'author_id' => 'sometimes|integer|exists:admin_mst,id',
        ];
    }
}
