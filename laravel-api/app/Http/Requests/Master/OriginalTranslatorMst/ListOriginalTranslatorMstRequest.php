<?php

namespace App\Http\Requests\Master\OriginalTranslatorMst;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Master\OriginalTranslatorMst;
use App\Enums\IsDelete;

class ListOriginalTranslatorMstRequest extends FormRequest
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
            '"table"' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:64',],
            '"column"' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:64',],
            'field_id' => ['nullable', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'is_delete' => ['nullable', new Enum(IsDelete::class),],
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
                'after:from_date'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            '"table"' => __('messages."table"'),
            '"column"' => __('messages."column"'),
            'field_id' => __('messages.field_id'),
            'is_delete' => __('messages.is_delete'),
            'from_date' => __('messages.from_date'),
            'to_date' => __('messages.to_date'),
        ];
    }
}
