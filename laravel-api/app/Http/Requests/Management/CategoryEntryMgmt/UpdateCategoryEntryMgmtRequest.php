<?php

namespace App\Http\Requests\Management\CategoryEntryMgmt;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateCategoryEntryMgmtRequest extends FormRequest
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
            'insert.*.category_mgmt_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'insert.*.entry_mgmt_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'delete' => ['nullable', 'array'],
            'delete.*' => 'array',
            'delete.*.category_mgmt_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'delete.*.entry_mgmt_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
        ];
    }

    public function attributes(): array
    {
        return [
            'category_mgmt_id' => __('messages.category_mgmt_id'),
            'entry_mgmt_id' => __('messages.entry_mgmt_id'),
            'insert.*.category_mgmt_id' => __('messages.category_mgmt_id'),
            'delete.*.category_mgmt_id' => __('messages.category_mgmt_id'),
            'insert.*.entry_mgmt_id' => __('messages.entry_mgmt_id'),
            'delete.*.entry_mgmt_id' => __('messages.entry_mgmt_id'),
        ];
    }
}
