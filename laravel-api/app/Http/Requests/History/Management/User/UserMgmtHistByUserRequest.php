<?php

namespace App\Http\Requests\History\Management\User;

use Illuminate\Foundation\Http\FormRequest;

class UserMgmtHistByUserRequest extends FormRequest
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
            'action' => 'sometimes|integer|in:1,2,3',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|in:id,action,created_at',
            'sort_direction' => 'sometimes|string|in:asc,desc',
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
            'action' => __('messages.action'),
            'per_page' => __('messages.per_page'),
            'sort_by' => __('messages.sort_by'),
            'sort_direction' => __('messages.sort_direction'),
        ];
    }
}
