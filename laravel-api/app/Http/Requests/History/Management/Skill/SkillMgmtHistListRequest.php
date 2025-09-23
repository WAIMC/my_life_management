<?php

namespace App\Http\Requests\History\Management\Skill;

use Illuminate\Foundation\Http\FormRequest;

class SkillMgmtHistListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'skill_mgmt_id' => 'sometimes|integer|exists:skill_mgmt,id',
            'action' => 'sometimes|integer|between:1,3',
            'author_id' => 'sometimes|integer|exists:admin_mst,id',
            'from_date' => 'sometimes|date_format:Y-m-d',
            'to_date' => 'sometimes|date_format:Y-m-d|after_or_equal:from_date',
            'per_page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:id,skill_mgmt_id,action,author_id,created_at',
            'sort_order' => 'sometimes|string|in:asc,desc',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'skill_mgmt_id' => trans('messages.skill_id'),
            'action' => trans('messages.action'),
            'author_id' => trans('messages.author_id'),
            'from_date' => trans('messages.from_date'),
            'to_date' => trans('messages.to_date'),
            'per_page' => trans('messages.per_page'),
            'sort_by' => trans('messages.sort_by'),
            'sort_order' => trans('messages.sort_order'),
        ];
    }
}
