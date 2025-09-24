<?php

namespace App\Http\Requests\Management\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserMgmtRequest extends FormRequest
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
        $userId = $this->route('id');
        
        return [
            'role_id' => 'sometimes|integer|exists:role_mst,id',
            'department_id' => 'sometimes|integer|exists:department_mst,id',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:30',
                Rule::unique('user_mgmt', 'email')->ignore($userId, 'id')
            ],
            'user_name' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('user_mgmt', 'user_name')->ignore($userId, 'id')
            ],
            'password' => 'sometimes|string|min:8|max:100',
            'first_name' => 'sometimes|string|max:20',
            'last_name' => 'sometimes|string|max:20',
            'address' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'birth' => 'nullable|string',
            'gender' => 'sometimes|integer',
            'status' => 'sometimes|integer',
            'is_active' => 'sometimes|boolean',
            'avatar' => 'nullable|string|max:30',
            'email_verified_at' => 'nullable|string',
            'remember_token' => 'nullable|string|max:100',
            'updated_at' => 'nullable|string'
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
            'role_id' => 'Role ID',
            'department_id' => 'Department ID',
            'email' => 'Email',
            'user_name' => 'Username',
            'password' => 'Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'address' => 'Address',
            'phone_number' => 'Phone Number',
            'birth' => 'Birth',
            'gender' => 'Gender',
            'status' => 'Status',
            'is_active' => 'Active Status',
            'avatar' => 'Avatar',
            'email_verified_at' => 'Email Verified At',
            'remember_token' => 'Remember Token',
            'updated_at' => 'Updated At'
        ];
    }
}