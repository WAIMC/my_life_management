<?php

namespace App\Http\Requests\History\Master\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreApiMstHistRequest extends FormRequest
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
            'api_mst_id' => 'required|integer|exists:api_mst,id',
            'type' => 'nullable|integer',
            'name' => 'nullable|string|max:50',
            'path' => 'nullable|string|max:100',
            'is_active' => 'nullable|integer|in:0,1',
            'feature_id' => 'required|integer|exists:feature_mst,id',
            'action' => 'required|integer|in:1,2,3',
            'author_id' => 'required|integer',
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
            'type' => 'Type',
            'name' => 'Name',
            'path' => 'Path',
            'is_active' => 'Active Status',
            'feature_id' => 'Feature ID',
            'action' => 'Action',
            'author_id' => 'Author ID',
        ];
    }
}
