<?php

namespace App\Http\Requests\Master\OriginalTranslatorMst;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Master\OriginalTranslatorMst;
use App\Enums\IsDelete;

class UpdateOriginalTranslatorMstRequest extends FormRequest
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
            'id' => ['required', 'integer', 'min:1', Rule::exists(OriginalTranslatorMst::class, 'id')],
            '"table"' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:64',],
            '"column"' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:64',],
            'field_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'is_delete' => ['required', new Enum(IsDelete::class),],
        ];
    }

    public function attributes(): array
    {
        return [
            '"table"' => __('messages."table"'),
            '"column"' => __('messages."column"'),
            'field_id' => __('messages.field_id'),
            'is_delete' => __('messages.is_delete'),
        ];
    }
}
