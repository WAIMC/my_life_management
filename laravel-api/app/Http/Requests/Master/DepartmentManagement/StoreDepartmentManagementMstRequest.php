<?php

namespace App\Http\Requests\Master\DepartmentManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentManagementMstRequest extends FormRequest
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
            'department_id' => 'required|integer|exists:department_mst,id',
            'policy_department_id' => 'required|integer|exists:policy_department_mst,id',
            'created_at' => 'nullable|string',
            'updated_at' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'department_id.required' => 'Department ID is required',
            'department_id.exists' => 'The referenced department does not exist',
            'policy_department_id.required' => 'Policy department ID is required',
            'policy_department_id.exists' => 'The referenced policy department does not exist',
        ];
    }
}
