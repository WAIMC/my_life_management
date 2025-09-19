<?php

namespace App\Http\Requests\Master\Department;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentMstRequest extends FormRequest
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
            'code' => 'sometimes|required|string|max:50|unique:department_mst,code,' . $this->route('department'),
            'name' => 'sometimes|required|string|max:50',
            'status' => 'sometimes|required|integer',
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
            'code.required' => 'Department code is required',
            'code.unique' => 'A department with this code already exists',
            'name.required' => 'Department name is required',
            'status.required' => 'Department status is required',
        ];
    }
}
