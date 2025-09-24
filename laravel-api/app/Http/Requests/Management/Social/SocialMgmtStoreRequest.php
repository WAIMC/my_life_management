<?php

namespace App\Http\Requests\Management\Social;

use Illuminate\Foundation\Http\FormRequest;

class SocialMgmtStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'slug' => 'nullable|string|max:50',
            'link' => 'required|string|max:255|url',
            'image' => 'required|string|max:100',
            'status' => 'required|integer',
            'is_display' => 'required|boolean',
            'rank_order' => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
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
