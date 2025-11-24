<?php

namespace App\Http\Requests\Management\GoogleDriveConfig;

use Illuminate\Foundation\Http\FormRequest;

class UploadCredentialsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:json|max:1024', // Max 1MB
            'name' => 'required|string|max:100',
            'root_folder_id' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Credentials file is required',
            'file.mimes' => 'File must be a JSON file',
            'name.required' => 'Configuration name is required',
            'root_folder_id.required' => 'Root folder ID is required',
        ];
    }
}
