<?php

namespace App\Http\Requests\Management\Product;

use Illuminate\Validation\Rule;

class UpdateProductMgmtRequest extends ProductMgmtRequest
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
            'code' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('product_mgmt', 'code')->ignore($this->route('product_mgmt'))
            ],
            'name' => 'sometimes|string|max:100',
            'slug' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('product_mgmt', 'slug')->ignore($this->route('product_mgmt'))
            ],
            'description' => 'sometimes|string|max:255',
            'status' => 'sometimes|integer',
            'is_display' => 'sometimes|boolean',
            'rank_order' => 'sometimes|integer',
        ];
    }
}
