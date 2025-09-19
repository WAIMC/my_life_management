<?php

namespace App\Http\Requests\Master\Api;

use App\Constants\CommonVal;
use App\Enums\IsActive;
use App\Enums\TypeOfMethod;
use App\Models\Master\ApiMst;
use App\Models\Master\FeatureMst;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ApiMstUpdateRequest extends FormRequest
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
        $payload = $this->all();

        return [
            'type' => ['required', new Enum(TypeOfMethod::class)],
            'name' => [
                'required',
                'string',
                'min:' . CommonVal::MIN_VARCHAR,
                'max:' . CommonVal::MAX_VARCHAR,
                Rule::unique(ApiMst::class)->ignore($payload['id'])
            ],
            'path' => [
                'required',
                'string',
                'min:' . CommonVal::MIN_VARCHAR,
                'max:' . CommonVal::MAX_VARCHAR,
                Rule::unique(ApiMst::class)->ignore($payload['id'])
            ],
            'is_active' => ['nullable', new Enum(IsActive::class)],
            'feature_id' => [
                'required',
                'numeric',
                'min:' . CommonVal::MIN_INTEGER,
                'max:' . CommonVal::MAX_INTEGER,
                Rule::exists(FeatureMst::class, 'id'),
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'type' => __('message.type_of_method'),
            'name' => __('message.api_name'),
            'path' => __('message.api_path'),
            'is_active' => __('message.is_active'),
            'feature_id' => __('message.feature_id'),
        ];
    }
}
