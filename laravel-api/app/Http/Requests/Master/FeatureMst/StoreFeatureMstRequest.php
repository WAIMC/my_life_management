<?php

namespace App\Http\Requests\Master\FeatureMst;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Master\FeatureMst;
use App\Enums\IsDelete;
use App\Enums\StatusEnum;

class StoreFeatureMstRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
            'group_name' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:50',],
            'description' => ['required', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:100',],
            'status' => ['required', new Enum(StatusEnum::class),],
            'is_delete' => ['required', new Enum(IsDelete::class),],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('messages.name'),
            'group_name' => __('messages.group_name'),
            'description' => __('messages.description'),
            'status' => __('messages.status'),
            'is_delete' => __('messages.is_delete'),
        ];
    }
}
