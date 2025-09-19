<?php

namespace App\Http\Requests\History\Master\ApiMstHist;

use Illuminate\Foundation\Http\FormRequest;

class ApiMstHistListRequest extends FormRequest
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
            'api_mst_id' => 'integer|exists:api_mst,id',
            'action' => 'integer|in:1,2,3',
            'author_id' => 'integer',
            'feature_id' => 'integer|exists:feature_mst,id',
            'per_page' => 'integer|min:1',
            'sort_by' => 'string|in:id,api_mst_id,action,created_at',
            'sort_order' => 'string|in:asc,desc',
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
            'api_mst_id' => 'API ID',
            'action' => 'Action',
            'author_id' => 'Author ID',
            'feature_id' => 'Feature ID',
            'per_page' => 'Per Page',
            'sort_by' => 'Sort By',
            'sort_order' => 'Sort Order',
        ];
    }
}
