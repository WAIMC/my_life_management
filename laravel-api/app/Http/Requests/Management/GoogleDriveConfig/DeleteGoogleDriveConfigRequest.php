<?php

namespace App\Http\Requests\Management\GoogleDriveConfig;

use Illuminate\Foundation\Http\FormRequest;

class DeleteGoogleDriveConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:google_drive_configs,id',
        ];
    }
}
