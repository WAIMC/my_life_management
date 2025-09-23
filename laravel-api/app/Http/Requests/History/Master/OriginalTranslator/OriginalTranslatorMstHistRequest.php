<?php

namespace App\Http\Requests\History\Master\OriginalTranslator;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OriginalTranslatorMstHistRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'original_translator_mst_id' => __('messages.original_translator_mst_id'),
            'table' => __('messages.table'),
            'column' => __('messages.column'),
            'field_id' => __('messages.field_id'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id'),
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'code' => 422,
            'message' => $validator->errors(),
            'data' => null,
        ], 422));
    }
}
