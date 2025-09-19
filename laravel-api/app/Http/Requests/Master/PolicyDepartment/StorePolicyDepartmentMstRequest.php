<?php

namespace App\Http\Requests\Master\PolicyDepartment;

use Illuminate\Foundation\Http\FormRequest;

class StorePolicyDepartmentMstRequest extends FormRequest
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
            'id' => 'required|integer|unique:policy_department_mst,id',
            'table_name' => 'required|string|max:20',
            'row_id' => 'required|integer',
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
            'id.unique' => 'A policy department with this ID already exists',
            'table_name.required' => 'Table name is required',
            'row_id.required' => 'Row ID is required',
        ];
    }
}
