<?php

namespace App\Http\Requests\Master\Admin;

use App\Constants\CommonVal;
use App\Enums\AdminStatus;
use App\Enums\Gender;
use App\Enums\IsActive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AdminUpdateRequest extends FormRequest
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
            'id' => [
                'required',
                'numeric',
                'min:' . CommonVal::MIN_INTEGER,
                'max:' . CommonVal::MAX_INTEGER,
                'exists:App\Models\Master\Admin,id',
            ],
            'password' => [
                'required',
                'string',
                'min:' . CommonVal::MIN_VARCHAR,
                'max:' . CommonVal::MAX_VARCHAR,
            ],
            'first_name' => [
                'required',
                'string',
                'min:' . CommonVal::MIN_VARCHAR,
                'max:' . CommonVal::MAX_VARCHAR,
            ],
            'last_name' => [
                'required',
                'string',
                'min:' . CommonVal::MIN_VARCHAR,
                'max:' . CommonVal::MAX_VARCHAR,
            ],
            'address' => [
                'required',
                'string',
                'min:' . CommonVal::MIN_VARCHAR,
                'max:' . CommonVal::MAX_VARCHAR,
            ],
            'phone_number' => [
                'required',
                'string',
                'min:' . CommonVal::MIN_VARCHAR,
                'max:' . CommonVal::MAX_PHONE_NUMBER,
            ],
            'birth' => [
                'nullable',
                'date_format:' . CommonVal::DATE_FORMAT,
                'after_or_equal:' . CommonVal::MIN_DATE,
                'before_or_equal:' . CommonVal::MAX_DATE,
            ],
            'gender' => ['nullable', new Enum(Gender::class)],
            'status' => ['nullable', new Enum(AdminStatus::class)],
            'is_active' => ['nullable', new Enum(IsActive::class)],
            'avatar' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR],
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
            'id' => __('message.admin_id'),
            'first_name' => __('message.first_name'),
            'last_name' => __('message.last_name'),
            'address' => __('message.address'),
            'phone_number' => __('message.phone_number'),
            'birth' => __('message.birth'),
            'gender' => __('message.gender'),
            'status' => __('message.status'),
            'is_active' => __('message.is_active'),
            'avatar' => __('message.avatar'),
        ];
    }
}
