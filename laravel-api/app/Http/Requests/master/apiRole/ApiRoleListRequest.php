<?php

namespace App\Http\Requests\master\apiRole;

use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\ApiRole;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApiRoleListRequest extends FormRequest
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
      'api_id'    => 'numeric|min:0',
      'role_id'   => 'numeric|min:0',
      'from_date' => 'date_format:' . CommonVal::DATE_FORMAT,
      'to_date'   => 'after:from_date|date_format:'  . CommonVal::DATE_FORMAT,
    ];
  }

  /**
   * Configure the validator instance.
   *
   * @param  \Illuminate\Validation\Validator  $validator
   * @return void
   */
  public function withValidator(Validator $validator)
  {
    $validator->after(function ($validator) {
      if ($this->from_date && $this->to_date) {
        $fromDate = \DateTime::createFromFormat(CommonVal::DATE_FORMAT, $this->from_date);
        $toDate = \DateTime::createFromFormat(CommonVal::DATE_FORMAT, $this->to_date);

        if ($fromDate >= $toDate) {
          $validator->errors()->add(
            'to_date',
            Messages::getMessage(
              Messages::E0014,
              [
                'attributes' => $this->attributes()['from_date'],
                'attributes2' => $this->attributes()['to_date']
              ]
            )
          );
        }
      }
    });
  }

  /**
   * Get custom attributes for validator errors.
   *
   * @return array<string, string>
   */
  public function attributes(): array
  {
    return [
      'from_date' => 'From date',
      'to_date' => 'To date',
    ];
  }

  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      /**
       * Api ID
       */
      'api_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => ApiRole::attributes()['api_id']]
      ),
      'api_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => ApiRole::attributes()['api_id'],
          'number' => ApiRole::LENGTH_ATTR[0]
        ]
      ),

      /**
       * Role ID
       */
      'role_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => ApiRole::attributes()['role_id']]
      ),
      'role_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => ApiRole::attributes()['role_id'],
          'number' => ApiRole::LENGTH_ATTR[0]
        ]
      ),

      /**
       * From date
       */
      'from_date.date_format' => Messages::getMessage(
        Messages::E0013,
        ['attributes' => $this->attributes()['from_date']]
      ),

      /**
       * To date
       */
      'to_date.date_format' => Messages::getMessage(
        Messages::E0013,
        ['attributes' => $this->attributes()['to_date']]
      ),
    ];
  }
}
