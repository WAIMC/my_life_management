<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StoreOriginalTranslatorMstRequest extends FormRequest
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
            'table' => 'required|string|max:64',
            'column' => 'required|string|max:64',
            'field_id' => 'required|integer'
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
            'field_id' => __('messages.field_id')
        ];
    }
}
