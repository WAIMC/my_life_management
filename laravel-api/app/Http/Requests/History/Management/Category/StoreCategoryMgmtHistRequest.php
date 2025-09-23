<?php

namespace App\Http\Requests\History\Management\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryMgmtHistRequest extends FormRequest
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
            'category_mgmt_id' => 'required|integer|exists:category_mgmt,id',
            'parent_id' => 'nullable|integer',
            'name' => 'nullable|string|max:50',
            'slug' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:150',
            'status' => 'nullable|integer',
            'is_display' => 'nullable|boolean',
            'rank_order' => 'nullable|integer',
            'action' => 'required|integer|in:1,2,3',
            'author_id' => 'required|integer|exists:admin_mst,id',
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
            'category_mgmt_id' => 'Category ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'status' => 'Status',
            'is_display' => 'Display Status',
            'rank_order' => 'Rank Order',
            'action' => 'Action',
            'author_id' => 'Author ID',
        ];
    }
}
