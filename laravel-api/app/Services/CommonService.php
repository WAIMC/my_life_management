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
}
