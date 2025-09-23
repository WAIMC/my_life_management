<?php

namespace App\Http\Requests\History\Master\Feature;

use Illuminate\Foundation\Http\FormRequest;

class FeatureMstHistListRequest extends FormRequest
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
            'name' => 'sometimes|string|max:50',
            'group_name' => 'sometimes|string|max:50',
            'description' => 'sometimes|string|max:100',
            'status' => 'sometimes|integer',
            'action' => 'sometimes|integer',
            'author_id' => 'sometimes|integer',
            'created_from' => 'sometimes|date_format:Y-m-d H:i:s',
            'created_to' => 'sometimes|date_format:Y-m-d H:i:s',
            'order_by' => 'sometimes|string|in:id,feature_mst_id,name,group_name,description,status,action,author_id,created_at',
            'order' => 'sometimes|string|in:asc,desc',
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
            'feature_mst_id' => __('messages.feature_id'),
            'name' => __('messages.feature_name'),
            'group_name' => __('messages.feature_group_name'),
            'description' => __('messages.feature_description'),
            'status' => __('messages.status'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id'),
            'created_from' => __('messages.created_at'),
            'created_to' => __('messages.created_at'),
            'order_by' => __('messages.order_by'),
            'order' => __('messages.order'),
            'per_page' => __('messages.per_page')
        ];
    }
}
