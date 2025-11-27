<?php

namespace App\Http\Requests\Management\MediaFile;

use Illuminate\Foundation\Http\FormRequest;

class CreateFolderRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'folder_path' => ['nullable', 'string', 'max:500'],
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
            'name.required' => 'Folder name is required',
            'name.string' => 'Folder name must be a string',
            'name.max' => 'Folder name must not exceed 255 characters',
            'folder_path.string' => 'Folder path must be a string',
            'folder_path.max' => 'Folder path must not exceed 500 characters',
        ];
    }
}
