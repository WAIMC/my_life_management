<?php

namespace App\Http\Requests\Management\Banner;

use Illuminate\Foundation\Http\FormRequest;

class DeleteBannerMgmtRequest extends FormRequest
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
            'author_id' => 'Author ID',
        ];
    }
}
