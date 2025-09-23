<?php

namespace App\Http\Requests\History\Master\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentMstHistRequest extends FormRequest
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
            'id' => 'sometimes|integer',
            'department_mst_id' => 'required|integer|exists:department_mst,id',
            'code' => 'nullable|string|max:50',
            'name' => 'nullable|string|max:50',
            'status' => 'nullable|integer',
            'action' => 'required|integer',
            'author_id' => 'sometimes|integer'
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
            'id' => __('messages.id'),
            'department_mst_id' => __('messages.department_id'),
            'code' => __('messages.code'),
            'name' => __('messages.name'),
            'status' => __('messages.status'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id')
        ];
    }
}
