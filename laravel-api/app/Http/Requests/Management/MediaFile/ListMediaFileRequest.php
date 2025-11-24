<?php

namespace App\Http\Requests\Management\MediaFile;

use Illuminate\Foundation\Http\FormRequest;

class ListMediaFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'folder_path' => 'sometimes|string|max:500',
            'file_type' => 'sometimes|string|in:images,videos,documents',
            'mime_type' => 'sometimes|string|max:100',
            'search' => 'sometimes|string|max:255',
            'status' => 'sometimes|integer',
            'order_by' => 'sometimes|string|in:created_at,original_name,size',
            'order_direction' => 'sometimes|string|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
        ];
    }
}
