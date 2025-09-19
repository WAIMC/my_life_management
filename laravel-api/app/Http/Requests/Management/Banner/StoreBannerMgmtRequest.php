<?php

namespace App\Http\Requests\Management\Banner;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerMgmtRequest extends FormRequest
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
            'title' => 'required|string|max:50',
            'slug' => 'nullable|string|max:50|unique:banner_mgmt,slug',
            'description' => 'required|string|max:255',
            'link' => 'required|string|max:100',
            'image' => 'required|string|max:100',
            'position' => 'required|string|max:50',
            'status' => 'required|integer',
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
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'link' => 'Link',
            'image' => 'Image',
            'position' => 'Position',
            'status' => 'Status',
            'author_id' => 'Author ID',
        ];
    }
}
