<?php

namespace App\Http\Requests\Management\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryMgmtRequest extends FormRequest
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
            'parent_id' => 'required|integer|min:0',
            'name' => 'required|string|max:50',
            'slug' => 'nullable|string|max:50|unique:category_mgmt,slug',
            'description' => 'nullable|string|max:150',
            'status' => 'required|integer',
            'is_display' => 'required|boolean',
            'rank_order' => 'nullable|integer|min:0',
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
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'status' => 'Status',
            'is_display' => 'Display Status',
            'rank_order' => 'Rank Order',
            'author_id' => 'Author ID',
        ];
    }
}
