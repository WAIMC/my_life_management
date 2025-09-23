<?php

namespace App\Http\Requests\History\Management\Skill;

use Illuminate\Foundation\Http\FormRequest;

class StoreSkillMgmtHistRequest extends FormRequest
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
            'skill_mgmt_id' => 'required|integer|exists:skill_mgmt,id',
            'parent_id' => 'nullable|integer|exists:skill_mgmt,id',
            'name' => 'nullable|string|max:50',
            'slug' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:150',
            'status' => 'nullable|integer',
            'is_display' => 'nullable|boolean',
            'rank_order' => 'nullable|integer',
            'action' => 'required|integer|between:1,3',
            'author_id' => 'required|integer|exists:admin_mst,id',
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
            'parent_id' => trans('messages.parent_id'),
            'name' => trans('messages.skill_name'),
            'slug' => trans('messages.skill_slug'),
            'description' => trans('messages.description'),
            'status' => trans('messages.skill_status'),
            'is_display' => trans('messages.is_display'),
            'rank_order' => trans('messages.skill_rank_order'),
            'action' => trans('messages.action'),
            'author_id' => trans('messages.author_id'),
        ];
    }
}
