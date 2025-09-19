<?php

namespace App\Http\Requests\Master\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleMstRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:30|unique:role_mst,name,' . $this->route('role'),
            'permission' => 'sometimes|required|string|max:50',
            'is_active' => 'sometimes|required|boolean',
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
            'name.required' => 'Role name is required',
            'name.unique' => 'A role with this name already exists',
            'permission.required' => 'Role permission is required',
            'is_active.required' => 'Role active status is required',
        ];
    }
}
