<?php

namespace App\Http\Requests\History\Master\LanguageMstHist;

use App\Http\Requests\BaseFormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\History\Master\LanguageMstHist;
use App\Enums\IsActive;
use App\Models\Master\LanguageMst;

class StoreLanguageMstHistRequest extends BaseFormRequest
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
            'language_mst_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER, Rule::exists(LanguageMst::class, 'id'),],
            'abbreviation' => ['string', 'min:' . CommonVal::MIN_VARCHAR, 'max:10',],
            'name' => ['string', 'min:' . CommonVal::MIN_VARCHAR, 'max:30',],
            'is_active' => [new Enum(IsActive::class),],
            'action' => ['required',],
            'author_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
        ];
    }

    public function attributes(): array
    {
        return [
            'language_mst_id' => __('messages.language_mst_id'),
            'abbreviation' => __('messages.abbreviation'),
            'name' => __('messages.name'),
            'is_active' => __('messages.is_active'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id'),
        ];
    }
}
