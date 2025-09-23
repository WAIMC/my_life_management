<?php

namespace App\Http\Requests\Management\CategorySkill;

use Illuminate\Foundation\Http\FormRequest;

class CategorySkillMgmtListRequest extends FormRequest
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
            'category_id' => 'nullable|integer|exists:category_mgmt,id',
            'skill_id' => 'nullable|integer|exists:skill_mgmt,id',
            'per_page' => 'nullable|integer|min:1',
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
            'category_id' => 'Category ID',
            'skill_id' => 'Skill ID',
            'per_page' => 'Per Page',
        ];
    }
}
