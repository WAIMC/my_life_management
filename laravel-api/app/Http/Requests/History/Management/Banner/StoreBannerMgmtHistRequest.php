<?php

namespace App\Http\Requests\History\Management\Banner;

use App\Models\Management\BannerMgmt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\CommonVal;
use App\Enums\ActionType;
use App\Enums\StatusEnum;

class StoreBannerMgmtHistRequest extends FormRequest
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
            'banner_mgmt_id' => ['required', 'integer', Rule::exists(BannerMgmt::class, 'id')],
            'title' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR],
            'slug' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR],
            'description' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR],
            'link' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR],
            'image' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR],
            'position' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR],
            'status' => ['nullable', 'integer', Rule::enum(StatusEnum::class)],
            'action' => ['required', 'integer', Rule::enum(ActionType::class)],
            'author_id' => ['required', 'integer', 'exists:admin_mst,id'],
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
            'banner_mgmt_id' => 'Banner ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'link' => 'Link',
            'image' => 'Image',
            'position' => 'Position',
            'status' => 'Status',
            'action' => 'Action',
            'author_id' => 'Author ID',
        ];
    }
}
