<?php

namespace App\Http\Requests\Management\Product;

class ProductMgmtListRequest extends ProductMgmtRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|integer|exists:category_mgmt,id',
            'status' => 'sometimes|integer',
            'is_display' => 'sometimes|boolean',
            'search' => 'sometimes|string|max:100',
            'sort_by' => 'sometimes|string|in:id,name,code,status,rank_order,created_at',
            'sort_order' => 'sometimes|string|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ];
    }
}
