<?php

namespace App\Http\Requests\Management\MediaFile;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaFileRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:51200', // 50MB max
                'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,mp4,mov,avi,mkv'
            ],
            'is_public' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'File is required',
            'file.file' => 'Invalid file',
            'file.max' => 'File size must not exceed 50MB',
            'file.mimes' => 'File type not allowed. Allowed types: images (jpg, png, gif, webp), documents (pdf, doc, docx, xls, xlsx), videos (mp4, mov, avi, mkv)',
        ];
    }
}
