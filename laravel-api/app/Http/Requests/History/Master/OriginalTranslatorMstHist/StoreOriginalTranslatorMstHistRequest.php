<?php

namespace App\Http\Requests\History\Master\OriginalTranslatorMstHist;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\History\Master\OriginalTranslatorMstHist;

use App\Models\Master\OriginalTranslatorMst;

class StoreOriginalTranslatorMstHistRequest extends FormRequest
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
            'original_translator_mst_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER, Rule::exists(OriginalTranslatorMst::class, 'id'),],
            '"table"' => ['string', 'min:' . CommonVal::MIN_VARCHAR, 'max:64',],
            '"column"' => ['string', 'min:' . CommonVal::MIN_VARCHAR, 'max:64',],
            'field_id' => ['integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'action' => ['required',],
            'author_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
        ];
    }

    public function attributes(): array
    {
        return [
            'original_translator_mst_id' => __('messages.original_translator_mst_id'),
            '"table"' => __('messages."table"'),
            '"column"' => __('messages."column"'),
            'field_id' => __('messages.field_id'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id'),
        ];
    }
}
