<?php

namespace App\Http\Requests\History\Management\Banner;

use Illuminate\Foundation\Http\FormRequest;

class BannerMgmtHistListRequest extends FormRequest
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
            'banner_mgmt_id' => 'nullable|integer|exists:banner_mgmt,id',
            'action' => 'nullable|integer|in:1,2,3',
            'author_id' => 'nullable|integer|exists:admin_mst,id',
            'from_date' => 'nullable|date_format:Y-m-d H:i:s',
            'to_date' => 'nullable|date_format:Y-m-d H:i:s',
            'per_page' => 'nullable|integer|min:1',
            'sort_by' => 'nullable|string|in:id,banner_mgmt_id,action,created_at',
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
            'banner_mgmt_id' => 'Banner ID',
            'action' => 'Action',
            'author_id' => 'Author ID',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'per_page' => 'Per Page',
            'sort_by' => 'Sort By',
            'sort_order' => 'Sort Order',
        ];
    }
}
