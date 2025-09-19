<?php

namespace App\Http\Requests\Master\AdminDepartment;

use App\Constants\CommonVal;
use Illuminate\Foundation\Http\FormRequest;

class AdminDepartmentMstListRequest extends FormRequest
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
            'admin_id' => ['nullable', 'numeric', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'department_id' => ['nullable', 'numeric', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'from_date' => [
                'nullable',
                'date_format:' . CommonVal::DATE_FORMAT,
                'after_or_equal:' . CommonVal::MIN_DATE,
                'before_or_equal:' . CommonVal::MAX_DATE,
            ],
            'to_date' => [
                'nullable',
                'date_format:' . CommonVal::DATE_FORMAT,
                'after_or_equal:' . CommonVal::MIN_DATE,
                'before_or_equal:' . CommonVal::MAX_DATE,
                'after:from_date',
            ],
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
            'admin_id' => __('messages.admin_id'),
            'department_id' => __('messages.department_id'),
            'from_date' => __('messages.from_date'),
            'to_date' => __('messages.to_date'),
        ];
    }
}
