<?php

namespace App\Http\Requests\History\Master\Department;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentMstHistListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'department_mst_id' => 'sometimes|integer|exists:department_mst,id',
            'code' => 'sometimes|string|max:50',
            'name' => 'sometimes|string|max:50',
            'status' => 'sometimes|integer',
            'action' => 'sometimes|integer',
            'author_id' => 'sometimes|integer',
            'created_from' => 'sometimes|date_format:Y-m-d H:i:s',
            'created_to' => 'sometimes|date_format:Y-m-d H:i:s',
            'order_by' => 'sometimes|string|in:id,department_mst_id,code,name,status,action,author_id,created_at',
            'order' => 'sometimes|string|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:100'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'department_mst_id' => __('messages.department_id'),
            'code' => __('messages.code'),
            'name' => __('messages.name'),
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
