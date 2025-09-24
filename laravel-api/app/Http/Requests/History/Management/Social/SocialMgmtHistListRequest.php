<?php

namespace App\Http\Requests\History\Management\Social;

use Illuminate\Foundation\Http\FormRequest;

class SocialMgmtHistListRequest extends FormRequest
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
            'social_mgmt_id' => 'nullable|integer',
            'name' => 'nullable|string|max:50',
            'status' => 'nullable|integer',
            'action' => 'nullable|integer',
            'author_id' => 'nullable|integer',
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
            'status' => 'Status',
            'action' => 'Action',
            'author_id' => 'Author ID',
        ];
    }
}
