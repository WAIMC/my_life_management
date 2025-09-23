<?php

namespace App\Http\Requests\History\Management\Product;

class ProductMgmtHistListRequest extends ProductMgmtHistRequest
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
            'category_id' => 'sometimes|integer|exists:category_mgmt,id',
            'action' => 'sometimes|integer',
            'author_id' => 'sometimes|integer',
            'search' => 'sometimes|string|max:100',
            'date_from' => 'sometimes|date_format:Y-m-d H:i:s',
            'date_to' => 'sometimes|date_format:Y-m-d H:i:s',
            'sort_by' => 'sometimes|string|in:id,product_mgmt_id,category_id,name,code,action,author_id,created_at',
            'sort_order' => 'sometimes|string|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ];
    }
}
