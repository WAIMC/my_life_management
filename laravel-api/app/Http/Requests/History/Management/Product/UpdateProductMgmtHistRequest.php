<?php

namespace App\Http\Requests\History\Management\Product;

class UpdateProductMgmtHistRequest extends ProductMgmtHistRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'product_mgmt_id' => 'sometimes|integer|exists:product_mgmt,id',
            'category_id' => 'nullable|integer|exists:category_mgmt,id',
            'code' => 'nullable|string|max:50',
            'name' => 'nullable|string|max:100',
            'slug' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'status' => 'nullable|integer',
            'is_display' => 'nullable|boolean',
            'rank_order' => 'nullable|integer',
            'action' => 'sometimes|integer',
            'author_id' => 'sometimes|integer',
        ];
    }
}
