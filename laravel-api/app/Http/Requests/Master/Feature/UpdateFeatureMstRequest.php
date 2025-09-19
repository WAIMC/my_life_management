<?php

namespace App\Http\Requests\Master\Feature;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureMstRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:50',
            'group_name' => 'sometimes|required|string|max:50',
            'description' => 'sometimes|required|string|max:100',
            'status' => 'sometimes|required|integer',
            'updated_at' => 'nullable|string',
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
            'name.required' => 'Feature name is required',
            'group_name.required' => 'Feature group name is required',
            'description.required' => 'Feature description is required',
            'status.required' => 'Feature status is required',
        ];
    }
}
