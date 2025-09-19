<?php

namespace App\Http\Requests\Master\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentMstRequest extends FormRequest
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
            'id' => 'required|integer|unique:department_mst,id',
            'code' => 'required|string|max:50|unique:department_mst,code',
            'name' => 'required|string|max:50',
            'status' => 'required|integer',
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
            'id.unique' => 'A department with this ID already exists',
            'code.required' => 'Department code is required',
            'code.unique' => 'A department with this code already exists',
            'name.required' => 'Department name is required',
            'status.required' => 'Department status is required',
        ];
    }
}
