<?php

namespace App\Http\Requests\History\Management\Banner;

use App\Constants\CommonVal;
use App\Models\History\Management\BannerMgmtHist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteBannerMgmtHistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => [
                'required',
                'integer',
                'min:' . CommonVal::MIN_INTEGER,
                'max:' . CommonVal::MAX_INTEGER,
                Rule::exists(BannerMgmtHist::class, 'id')
            ],
        ];
    }

    /**
     * Get custom attribute names for validator errors
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'ids' =>  __('message.banner_mgmt_hist_id'),
            'ids.*' =>  __('message.banner_mgmt_hist_id'),
        ];
    }
}
