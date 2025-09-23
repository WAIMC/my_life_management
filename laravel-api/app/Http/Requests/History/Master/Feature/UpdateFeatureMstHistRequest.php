<?php

namespace App\Http\Requests\History\Master\Feature;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureMstHistRequest extends FormRequest
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
            'feature_mst_id' => 'sometimes|integer|exists:feature_mst,id',
            'name' => 'nullable|string|max:50',
            'group_name' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:100',
            'status' => 'nullable|integer',
            'action' => 'sometimes|integer',
            'author_id' => 'sometimes|integer'
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
            'feature_mst_id' => __('messages.feature_id'),
            'name' => __('messages.feature_name'),
            'group_name' => __('messages.feature_group_name'),
            'description' => __('messages.feature_description'),
            'status' => __('messages.status'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id')
        ];
    }
}
