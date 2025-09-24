<?php

namespace App\Http\Requests\Management\Social;

use Illuminate\Foundation\Http\FormRequest;

class SocialMgmtListRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:50',
            'status' => 'nullable|integer',
            'is_display' => 'nullable|boolean',
            'sort_by' => 'nullable|string|in:id,name,status,rank_order,created_at,updated_at',
            'sort_direction' => 'nullable|string|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
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
            'name' => 'Social name',
            'status' => 'Status',
            'is_display' => 'Display status',
            'sort_by' => 'Sort by',
            'sort_direction' => 'Sort direction',
            'per_page' => 'Items per page',
        ];
    }
}
