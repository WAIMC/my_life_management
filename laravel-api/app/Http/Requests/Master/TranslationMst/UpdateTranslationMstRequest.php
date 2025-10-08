<?php

namespace App\Http\Requests\Master\TranslationMst;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Master\TranslationMst;

class UpdateTranslationMstRequest extends FormRequest
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
            'id' => ['required', 'integer', 'min:1', Rule::exists(TranslationMst::class, 'id')],
            'language_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'original_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'value' => ['string', 'min:' . CommonVal::MIN_VARCHAR, 'max:255',],
        ];
    }

    public function attributes(): array
    {
        return [
            'language_id' => __('messages.language_id'),
            'original_id' => __('messages.original_id'),
            'value' => __('messages.value'),
        ];
    }
}
