<?php

namespace App\Http\Requests\Master\ApiRole;

use App\Constants\CommonVal;
use Illuminate\Foundation\Http\FormRequest;

class ApiRoleMstUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'insert' => ['nullable', 'array'],
            'insert.*' => 'array',
            'insert.*.api_id' => ['required', 'numeric', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'insert.*.role_id' => ['required', 'numeric', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'delete' => ['nullable', 'array'],
            'delete.*' => 'array',
            'delete.*.api_id' => ['required', 'numeric', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'delete.*.role_id' => ['required', 'numeric', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
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
            'insert' => __('messages.payload_insert'),
            'insert.*' => __('messages.item_insert'),
            'delete' => __('messages.payload_delete'),
            'delete.*' => __('messages.item_delete'),
            'admin_id' => __('messages.admin_id'),
            'department_id' => __('messages.department_id'),
        ];
    }
}
