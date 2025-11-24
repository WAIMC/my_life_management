<?php

namespace App\Http\Requests\Management\MediaFile;

use Illuminate\Foundation\Http\FormRequest;

class RenameMediaFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'new_name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'new_name.required' => 'New file name is required',
            'new_name.max' => 'File name must not exceed 255 characters',
        ];
    }
}
