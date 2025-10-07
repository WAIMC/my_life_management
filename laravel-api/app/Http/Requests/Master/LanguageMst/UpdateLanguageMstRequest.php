<?php

namespace App\Http\Requests\Master\LanguageMst;

use App\Http\Requests\BaseFormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Master\LanguageMst;
use App\Enums\IsActive;
use App\Enums\IsDelete;

class UpdateLanguageMstRequest extends BaseFormRequest
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
            'id' => ['required', 'integer', 'min:1', Rule::exists(LanguageMst::class, 'id')],
            'abbreviation' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:10',],
            'name' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:30',],
            'is_active' => ['required', new Enum(IsActive::class),],
            'is_delete' => ['required', new Enum(IsDelete::class),],
        ];
    }

    public function attributes(): array
    {
        return [
            'abbreviation' => __('messages.abbreviation'),
            'name' => __('messages.name'),
            'is_active' => __('messages.is_active'),
            'is_delete' => __('messages.is_delete'),
        ];
    }
}
