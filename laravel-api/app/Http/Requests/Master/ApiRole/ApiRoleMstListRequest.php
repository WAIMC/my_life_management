<?php

namespace App\Http\Requests\Master\ApiRole;

use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\Master\ApiRoleMst;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApiRoleMstListRequest extends FormRequest
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
            'api_id' => ['nullable', 'numeric', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'role_id' => ['nullable', 'numeric', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
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
            'api_id' => __('messages.api_id'),
            'role_id' => __('messages.role_id'),
            'from_date' => __('messages.from_date'),
            'to_date' => __('messages.to_date'),
        ];
    }
}
