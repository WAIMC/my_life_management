<?php

namespace App\Http\Requests\Master\TranslationLanguageMst;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateTranslationLanguageMstRequest extends FormRequest
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
            'insert.*.translation_mst_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'insert.*.language_mst_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'delete' => ['nullable', 'array'],
            'delete.*' => 'array',
            'delete.*.translation_mst_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
            'delete.*.language_mst_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER],
        ];
    }

    public function attributes(): array
    {
        return [
            'translation_mst_id' => __('messages.translation_mst_id'),
            'language_mst_id' => __('messages.language_mst_id'),
            'insert.*.translation_mst_id' => __('messages.translation_mst_id'),
            'delete.*.translation_mst_id' => __('messages.translation_mst_id'),
            'insert.*.language_mst_id' => __('messages.language_mst_id'),
            'delete.*.language_mst_id' => __('messages.language_mst_id'),
        ];
    }
}
