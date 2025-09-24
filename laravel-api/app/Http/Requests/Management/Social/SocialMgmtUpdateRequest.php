<?php

namespace App\Http\Requests\Management\Social;

use Illuminate\Foundation\Http\FormRequest;

class SocialMgmtUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:50',
            'slug' => 'nullable|string|max:50',
            'link' => 'sometimes|required|string|max:255|url',
            'image' => 'sometimes|required|string|max:100',
            'status' => 'sometimes|required|integer',
            'is_display' => 'sometimes|required|boolean',
            'rank_order' => 'sometimes|required|integer|min:0',
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
            'name' => 'Social name',
            'slug' => 'Social slug',
            'link' => 'Social link',
            'image' => 'Social image',
            'status' => 'Status',
            'is_display' => 'Display status',
            'rank_order' => 'Rank order',
        ];
    }
}
