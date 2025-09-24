<?php

namespace App\Http\Requests\History\Management\Social;

use Illuminate\Foundation\Http\FormRequest;

class StoreSocialMgmtHistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'social_mgmt_id' => 'required|integer|exists:social_mgmt,id',
            'name' => 'nullable|string|max:50',
            'slug' => 'nullable|string|max:50',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:100',
            'status' => 'nullable|integer',
            'is_display' => 'nullable|boolean',
            'rank_order' => 'nullable|integer',
            'action' => 'required|integer',
            'author_id' => 'required|integer',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'social_mgmt_id' => 'Social ID',
            'name' => 'Social name',
            'slug' => 'Social slug',
            'link' => 'Social link',
            'image' => 'Social image',
            'status' => 'Status',
            'is_display' => 'Display status',
            'rank_order' => 'Rank order',
            'action' => 'Action',
            'author_id' => 'Author ID',
        ];
    }
}
