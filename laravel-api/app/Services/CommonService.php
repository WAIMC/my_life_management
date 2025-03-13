<?php

namespace App\Services;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

class CommonService
{
  /**
   * print raw SQL query
   * 
   * var_dump(vsprintf(str_replace(['?'], ['\'%s\''], $query->toSql()), $query->getBindings()));
   */

  /**
   * Validator request manual
   * 
   * @param mixed $callBack
   * @param array $request
   * @return Validator
   */
  public function validationManual(mixed $callBack, array $request): Validator
  {
    $validatorRequest = $callBack;
    $validatorRequest->merge($request);

    return ValidatorFacade::make(
      $validatorRequest->all(),
      $validatorRequest->rules(),
      $validatorRequest->messages()
    );
  }
}
