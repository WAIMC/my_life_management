<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ApiResponse;
use App\Constants\CommonVal;

abstract class BaseFormRequest extends FormRequest
{
    use ApiResponse;

    protected function failedValidation(Validator $validator)
    {
        $code = CommonVal::HTTP_UNPROCESSABLE_CONTENT;
        $errors = $validator->errors()->toArray();
        $response = self::errorResponse($errors, $code);
        throw new ValidationException($validator, $response);
    }
}
