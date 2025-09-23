<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOriginalTranslatorMstRequest extends FormRequest
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
            'table' => 'sometimes|string|max:64',
            'column' => 'sometimes|string|max:64',
            'field_id' => 'sometimes|integer'
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
            'table' => __('messages.table'),
            'column' => __('messages.column'),
            'field_id' => __('messages.field_id')
        ];
    }
}
