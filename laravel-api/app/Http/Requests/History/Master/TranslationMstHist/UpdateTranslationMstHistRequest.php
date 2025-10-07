<?php

namespace App\Http\Requests\History\Master\TranslationMstHist;

use App\Http\Requests\BaseFormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\History\Master\TranslationMstHist;

use App\Models\Master\TranslationMst;

class UpdateTranslationMstHistRequest extends BaseFormRequest
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
            'id' => ['required', 'integer', 'min:1', Rule::exists(TranslationMstHist::class, 'id')],
            'translation_mst_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER, Rule::exists(TranslationMst::class, 'id'),],
            'language_id' => ['integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'original_id' => ['integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'value' => ['string', 'min:' . CommonVal::MIN_VARCHAR, 'max:255',],
            'action' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'author_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
        ];
    }

    public function attributes(): array
    {
        return [
            'translation_mst_id' => __('messages.translation_mst_id'),
            'language_id' => __('messages.language_id'),
            'original_id' => __('messages.original_id'),
            'value' => __('messages.value'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id'),
        ];
    }
}
