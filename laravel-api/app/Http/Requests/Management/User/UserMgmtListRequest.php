<?php

namespace App\Http\Requests\Management\User;

use Illuminate\Foundation\Http\FormRequest;

class UserMgmtListRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable|integer',
            'role_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'email' => 'nullable|string|max:30',
            'user_name' => 'nullable|string|max:50',
            'first_name' => 'nullable|string|max:20',
            'last_name' => 'nullable|string|max:20',
            'gender' => 'nullable|integer',
            'status' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'created_at' => 'nullable|string',
            'with_role' => 'nullable|boolean',
            'with_department' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1',
            'sort_by' => 'nullable|string|in:id,role_id,department_id,email,user_name,first_name,last_name,gender,status,is_active,created_at',
            'sort_direction' => 'nullable|string|in:asc,desc'
        ];
    }

    /**
     * Get the attributes for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'department_id' => 'Department ID',
            'email' => 'Email',
            'user_name' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'status' => 'Status',
            'is_active' => 'Active Status',
            'created_at' => 'Created At',
            'with_role' => 'Include Role',
            'with_department' => 'Include Department',
            'per_page' => 'Per Page',
            'sort_by' => 'Sort By',
            'sort_direction' => 'Sort Direction'
        ];
    }
}