<?php

namespace App\Http\Requests\History\Management\User;

use App\Enums\ActionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserMgmtHistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_mgmt_id' => 'required|integer|exists:user_mgmt,id',
            'role_id' => 'nullable|integer|exists:role_mst,id',
            'department_id' => 'nullable|integer|exists:department_mst,id',
            'email' => 'nullable|string|email|max:30',
            'user_name' => 'nullable|string|max:50',
            'password' => 'nullable|string|max:100',
            'first_name' => 'nullable|string|max:20',
            'last_name' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'birth' => 'nullable|string',
            'gender' => 'nullable|integer',
            'status' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'avatar' => 'nullable|string|max:30',
            'email_verified_at' => 'nullable|string',
            'remember_token' => 'nullable|string|max:100',
            'action' => [
                'required',
                'integer',
                Rule::in([ActionEnum::INSERT, ActionEnum::UPDATE, ActionEnum::DELETE])
            ],
            'author_id' => 'sometimes|integer|exists:admin_mst,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user_mgmt_id' => __('messages.user_mgmt_id'),
            'role_id' => __('messages.role_id'),
            'department_id' => __('messages.department_id'),
            'email' => __('messages.email'),
            'user_name' => __('messages.user_name'),
            'password' => __('messages.password'),
            'first_name' => __('messages.first_name'),
            'last_name' => __('messages.last_name'),
            'address' => __('messages.address'),
            'phone_number' => __('messages.phone_number'),
            'birth' => __('messages.birth'),
            'gender' => __('messages.gender'),
            'status' => __('messages.status'),
            'is_active' => __('messages.is_active'),
            'avatar' => __('messages.avatar'),
            'email_verified_at' => __('messages.email_verified_at'),
            'remember_token' => __('messages.remember_token'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id'),
        ];
    }
}
