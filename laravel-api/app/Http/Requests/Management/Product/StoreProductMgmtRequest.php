<?php

namespace App\Http\Requests\Management\Product;

class StoreProductMgmtRequest extends ProductMgmtRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:category_mgmt,id',
            'code' => 'required|string|max:50|unique:product_mgmt,code',
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:product_mgmt,slug',
            'description' => 'required|string|max:255',
            'status' => 'required|integer',
            'is_display' => 'required|boolean',
            'rank_order' => 'required|integer',
        ];
    }
}
