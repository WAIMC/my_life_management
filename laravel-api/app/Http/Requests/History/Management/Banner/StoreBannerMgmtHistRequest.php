<?php

namespace App\Http\Requests\History\Management\Banner;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerMgmtHistRequest extends FormRequest
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
            'banner_mgmt_id' => 'required|integer|exists:banner_mgmt,id',
            'title' => 'nullable|string|max:50',
            'slug' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:50',
            'status' => 'nullable|integer',
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
            'banner_mgmt_id' => 'Banner ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'link' => 'Link',
            'image' => 'Image',
            'position' => 'Position',
            'status' => 'Status',
            'action' => 'Action',
            'author_id' => 'Author ID',
        ];
    }
}
