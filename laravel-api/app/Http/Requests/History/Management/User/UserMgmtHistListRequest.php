<?php

namespace App\Http\Requests\History\Management\User;

use App\Enums\ActionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserMgmtHistListRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_mgmt_id' => 'sometimes|integer|exists:user_mgmt,id',
            'action' => [
                'sometimes',
                'integer',
                Rule::in([ActionEnum::INSERT, ActionEnum::UPDATE, ActionEnum::DELETE])
            ],
            'author_id' => 'sometimes|integer|exists:admin_mst,id',
            'date_from' => 'sometimes|date_format:Y-m-d H:i:s',
            'date_to' => 'sometimes|date_format:Y-m-d H:i:s|after_or_equal:date_from',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|in:id,user_mgmt_id,action,author_id,created_at',
            'sort_direction' => 'sometimes|string|in:asc,desc',
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
            'user_mgmt_id' => __('messages.user_mgmt_id'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id'),
            'date_from' => __('messages.date_from'),
            'date_to' => __('messages.date_to'),
            'per_page' => __('messages.per_page'),
            'sort_by' => __('messages.sort_by'),
            'sort_direction' => __('messages.sort_direction'),
        ];
    }
}
