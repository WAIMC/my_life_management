<?php

namespace App\Http\Requests\Management\GoogleDriveConfig;

use Illuminate\Foundation\Http\FormRequest;

class ListGoogleDriveConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'sometimes|integer',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
        ];
    }
}
