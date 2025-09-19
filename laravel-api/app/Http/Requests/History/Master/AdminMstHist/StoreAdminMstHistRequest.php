<?php

namespace App\Http\Requests\History\Master\AdminMstHist;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminMstHistRequest extends FormRequest
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
            'id' => 'required|integer',
            'admin_mst_id' => 'required|integer|exists:admin_mst,id',
            'email' => 'nullable|string|max:30|email',
            'user_name' => 'nullable|string|max:50',
            'password' => 'nullable|string|max:100',
            'first_name' => 'nullable|string|max:20',
            'last_name' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'birth' => 'nullable|string',
            'gender' => 'nullable|integer',
            'status' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'avatar' => 'nullable|string|max:30',
            'email_verified_at' => 'nullable|string',
            'remember_token' => 'nullable|string|max:100',
            'action' => 'required|integer',
            'author_id' => 'required|integer|exists:admin_mst,id',
            'created_at' => 'required|string',
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
            'admin_mst_id.exists' => 'The referenced admin does not exist',
            'author_id.exists' => 'The referenced author does not exist',
        ];
    }
}
