<?php

namespace App\Http\Requests\master\api;

use App\Models\master\Api;
use App\Constants\Messages;
use App\constants\CommonVal;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ApiListRequest extends FormRequest
{
  const MIN = 0;
  const MAX_name = 50;
  const MAX_PATH = 100;

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
      'type' => 'in:' . implode(',', array_keys(Api::TYPE_OF_METHOD)),
      'name' => 'string|min:0|max:50',
      'path' => 'string|min:0|max:100',
      'is_active' => 'in:true,false',
      'feature_id' => 'integer',
      'from_date' => 'date_format:' . CommonVal::DATE_FORMAT,
      'to_date' => 'after:from_date|date_format:'  . CommonVal::DATE_FORMAT,
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
      'type' => 'Type of api',
      'name' => 'Api name',
      'path' => 'Api path',
      'is_active' => 'Api valid',
      'feature_id' => 'Feature ID',
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
       * Type
       */
      'type.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => $this->attributes()['type'],
          'range' => implode(',', array_keys(Api::TYPE_OF_METHOD))
        ]
      ),

      /**
       * Name
       */
      'name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => $this->attributes()['name']]
      ),
      'name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['name'],
          'number' => self::MIN
        ]
      ),
      'name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => $this->attributes()['name'],
          'number' => self::MAX_name
        ]
      ),

      /**
       * Path
       */
      'path.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => $this->attributes()['path']]
      ),
      'path.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['path'],
          'number' => self::MIN
        ]
      ),
      'path.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => $this->attributes()['path'],
          'number' => self::MAX_PATH
        ]
      ),

      /**
       * is_active
       */
      'is_active.in' => Messages::getMessage(
        Messages::E0005,
        ['attributes' => $this->attributes()['is_active']]
      ),

      /**
       * Feature ID
       */
      'feature_id.integer' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => $this->attributes()['feature_id']]
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
