<?php

namespace App\Http\Requests\Management\Skill;

use Illuminate\Foundation\Http\FormRequest;

class SkillMgmtListRequest extends FormRequest
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
            'name' => 'nullable|string|max:50',
            'status' => 'nullable|integer',
            'is_display' => 'nullable|boolean',
            'parent_id' => 'nullable|integer|min:0',
            'with_parent' => 'nullable|boolean',
            'with_children' => 'nullable|boolean',
            'sort_field' => 'nullable|string|in:id,name,status,is_display,rank_order,created_at,updated_at',
            'sort_order' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
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
            'name' => 'Skill name',
            'status' => 'Skill status',
            'is_display' => 'Skill display status',
            'parent_id' => 'Parent skill ID',
            'with_parent' => 'Include parent skill',
            'with_children' => 'Include child skills',
            'sort_field' => 'Sort field',
            'sort_order' => 'Sort order',
            'per_page' => 'Items per page',
            'page' => 'Page number',
        ];
    }
}
