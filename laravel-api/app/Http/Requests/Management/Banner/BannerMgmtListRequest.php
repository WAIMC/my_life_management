<?php

namespace App\Http\Requests\Management\Banner;

use Illuminate\Foundation\Http\FormRequest;

class BannerMgmtListRequest extends FormRequest
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
            'title' => 'nullable|string',
            'status' => 'nullable|integer',
            'position' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1',
            'sort_by' => 'nullable|string|in:id,title,status,position,created_at',
            'sort_order' => 'nullable|string|in:asc,desc',
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
            'status' => 'Status',
            'position' => 'Position',
            'per_page' => 'Per Page',
            'sort_by' => 'Sort By',
            'sort_order' => 'Sort Order',
        ];
    }
}
