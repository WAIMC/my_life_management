<?php

namespace App\Http\Requests\History\Master\Role;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateRoleMstHistRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'role_mst_id' => 'required|exists:role_mst,id',
            'name' => 'nullable|string|max:30',
            'permission' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'action' => 'required|integer|in:1,2,3',
            'author_id' => 'required|exists:admin_mst,id',
        ];
    }
}
