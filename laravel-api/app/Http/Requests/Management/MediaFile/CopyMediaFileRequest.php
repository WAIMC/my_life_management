<?php

namespace App\Http\Requests\Management\MediaFile;

use Illuminate\Foundation\Http\FormRequest;

class CopyMediaFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'exists:media_files,id'],
            'target_folder_path' => ['required', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'ids.required' => 'File IDs are required',
            'ids.array' => 'File IDs must be an array',
            'ids.min' => 'At least one file ID is required',
            'ids.*.required' => 'Each file ID is required',
            'ids.*.integer' => 'Each file ID must be an integer',
            'ids.*.exists' => 'One or more files do not exist',
            'target_folder_path.required' => 'Target folder path is required',
            'target_folder_path.string' => 'Target folder path must be a string',
            'target_folder_path.max' => 'Target folder path must not exceed 500 characters',
        ];
    }
}
