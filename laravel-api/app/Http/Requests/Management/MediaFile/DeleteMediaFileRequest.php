<?php

namespace App\Http\Requests\Management\MediaFile;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMediaFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:media_files,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'File IDs are required',
            'ids.array' => 'File IDs must be an array',
            'ids.*.integer' => 'Each file ID must be an integer',
            'ids.*.exists' => 'One or more file IDs do not exist',
        ];
    }
}
