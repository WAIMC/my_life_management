<?php

namespace App\Http\Requests\Management\CategorySkill;

use Illuminate\Foundation\Http\FormRequest;

class AttachSkillRequest extends FormRequest
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
            'skill_id' => 'required|integer|exists:skill_mgmt,id',
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
            'skill_id' => 'Skill ID',
        ];
    }
}
