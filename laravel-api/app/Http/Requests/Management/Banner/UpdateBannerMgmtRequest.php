<?php

namespace App\Http\Requests\Management\Banner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerMgmtRequest extends FormRequest
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
            'title' => 'nullable|string|max:50',
            'slug' => 'nullable|string|max:50|unique:banner_mgmt,slug,' . $this->route('id'),
            'description' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:50',
            'status' => 'nullable|integer',
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
