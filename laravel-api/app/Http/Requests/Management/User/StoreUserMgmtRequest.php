<?php

namespace App\Http\Requests\Management\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserMgmtRequest extends FormRequest
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
            'id' => 'required|integer',
            'role_id' => 'required|integer|exists:role_mst,id',
            'department_id' => 'required|integer|exists:department_mst,id',
            'email' => 'required|string|email|max:30|unique:user_mgmt,email',
            'user_name' => 'required|string|max:50|unique:user_mgmt,user_name',
            'password' => 'required|string|min:8|max:100',
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'address' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'birth' => 'nullable|string',
            'gender' => 'required|integer',
            'status' => 'required|integer',
            'is_active' => 'required|boolean',
            'avatar' => 'nullable|string|max:30',
            'email_verified_at' => 'nullable|string',
            'remember_token' => 'nullable|string|max:100',
            'created_at' => 'nullable|string',
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
            'id' => 'ID',
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At'
        ];
    }
}