<?php

namespace App\Http\Requests\Master\Api;

use App\Constants\CommonVal;
use App\Models\Master\Api;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApiDeleteRequest extends FormRequest
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
            'ids' => 'required|array',
            'ids.*' => [
                'required',
                'numeric',
                'min:' . CommonVal::MIN_INTEGER,
                'max:' . CommonVal::MAX_INTEGER,
                Rule::exists(Api::class, 'id'),
            ]
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
            'ids' => __('message.api_id'),
            'ids.*' => __('message.item_id'),
        ];
    }
}
