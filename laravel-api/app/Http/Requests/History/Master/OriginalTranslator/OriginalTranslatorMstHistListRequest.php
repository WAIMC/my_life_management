<?php

namespace App\Http\Requests\History\Master\OriginalTranslator;

class OriginalTranslatorMstHistListRequest extends OriginalTranslatorMstHistRequest
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
            'action' => 'sometimes|integer|in:1,2,3',
            'author_id' => 'sometimes|integer|exists:admin_mst,id',
            'per_page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:id,original_translator_mst_id,action,author_id,created_at',
            'sort_order' => 'sometimes|string|in:asc,desc',
        ];
    }
}
