<?php

namespace App\Http\Requests\Management\MediaFile;

use Illuminate\Foundation\Http\FormRequest;

class MoveMediaFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'new_folder_path' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'new_folder_path.required' => 'New folder path is required',
            'new_folder_path.max' => 'Folder path must not exceed 500 characters',
        ];
    }
}
