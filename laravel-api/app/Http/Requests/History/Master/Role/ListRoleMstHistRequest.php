<?php

namespace App\Http\Requests\History\Master\Role;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ListRoleMstHistRequest extends FormRequest
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
            'role_mst_id' => 'nullable|exists:role_mst,id',
            'author_id' => 'nullable|exists:admin_mst,id',
            'action' => 'nullable|integer|in:1,2,3',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'per_page' => 'nullable|integer|min:1',
            'sort_by' => 'nullable|string|in:id,role_mst_id,name,is_active,action,author_id,created_at',
            'sort_direction' => 'nullable|string|in:asc,desc',
        ];
    }
}
