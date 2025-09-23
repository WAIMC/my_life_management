<?php

namespace App\Http\Requests\Management\Skill;

use Illuminate\Foundation\Http\FormRequest;

class StoreSkillMgmtRequest extends FormRequest
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
            'parent_id' => 'nullable|integer|min:0|exists:skill_mgmt,id',
            'name' => 'required|string|max:50',
            'slug' => 'nullable|string|max:50|unique:skill_mgmt,slug',
            'status' => 'required|integer',
            'is_display' => 'required|boolean',
            'rank_order' => 'required|integer',
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
            'parent_id' => 'Parent skill ID',
            'name' => 'Skill name',
            'slug' => 'Skill slug',
            'status' => 'Skill status',
            'is_display' => 'Skill display status',
            'rank_order' => 'Skill order',
        ];
    }
}
