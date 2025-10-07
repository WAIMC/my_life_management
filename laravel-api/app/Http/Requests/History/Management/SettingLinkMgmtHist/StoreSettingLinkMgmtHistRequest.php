<?php

namespace App\Http\Requests\History\Management\SettingLinkMgmtHist;

use App\Http\Requests\BaseFormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\History\Management\SettingLinkMgmtHist;

class StoreSettingLinkMgmtHistRequest extends BaseFormRequest
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
            'setting_link_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'key' => ['string', 'min:' . CommonVal::MIN_VARCHAR, 'max:30',],
            'value' => ['string', 'min:' . CommonVal::MIN_VARCHAR, 'max:100',],
            'action' => ['required',],
            'author_id' => ['required', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
        ];
    }

    public function attributes(): array
    {
        return [
            'setting_link_id' => __('messages.setting_link_id'),
            'key' => __('messages.key'),
            'value' => __('messages.value'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id'),
        ];
    }
}
