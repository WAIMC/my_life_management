<?php

namespace App\Http\Requests\Management\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryMgmtListRequest extends FormRequest
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
            'name' => 'nullable|string',
            'status' => 'nullable|integer',
            'is_display' => 'nullable|boolean',
            'parent_id' => 'nullable|integer',
            'per_page' => 'nullable|integer|min:1',
            'sort_by' => 'nullable|string|in:id,name,status,rank_order,created_at',
            'sort_order' => 'nullable|string|in:asc,desc',
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
            'name' => 'Name',
            'status' => 'Status',
            'is_display' => 'Display Status',
            'parent_id' => 'Parent ID',
            'per_page' => 'Per Page',
            'sort_by' => 'Sort By',
            'sort_order' => 'Sort Order',
        ];
    }
}
