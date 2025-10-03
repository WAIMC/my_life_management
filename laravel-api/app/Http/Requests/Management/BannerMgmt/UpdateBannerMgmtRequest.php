<?php

namespace App\Http\Requests\Management\BannerMgmt;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Management\BannerMgmt;
use App\Enums\IsDelete;
use App\Enums\StatusEnum;

class UpdateBannerMgmtRequest extends FormRequest
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
            'id' => ['required', 'integer', 'min:1', Rule::exists(BannerMgmt::class, 'id')],
            'title' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
            'slug' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
            'description' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:255',],
            'link' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:100',],
            'image' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:100',],
            'position' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
            'status' => ['required', new Enum(StatusEnum::class),],
            'is_delete' => ['required', new Enum(IsDelete::class),],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => __('messages.title'),
            'slug' => __('messages.slug'),
            'description' => __('messages.description'),
            'link' => __('messages.link'),
            'image' => __('messages.image'),
            'position' => __('messages.position'),
            'status' => __('messages.status'),
            'is_delete' => __('messages.is_delete'),
        ];
    }
}
