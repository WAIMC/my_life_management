<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class OriginalTranslatorMstListRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'sometimes|integer',
            'table' => 'sometimes|string|max:64',
            'column' => 'sometimes|string|max:64',
            'field_id' => 'sometimes|integer',
            'created_at' => 'sometimes|date',
            'updated_at' => 'sometimes|date',
            'sort_by' => 'sometimes|string|in:id,table,column,field_id,created_at,updated_at',
            'sort_direction' => 'sometimes|string|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:100'
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
            'id' => __('messages.id'),
            'table' => __('messages.table'),
            'column' => __('messages.column'),
            'field_id' => __('messages.field_id'),
            'created_at' => __('messages.created_at'),
            'updated_at' => __('messages.updated_at'),
            'sort_by' => __('messages.sort_by'),
            'sort_direction' => __('messages.sort_direction'),
            'per_page' => __('messages.per_page')
        ];
    }
}
