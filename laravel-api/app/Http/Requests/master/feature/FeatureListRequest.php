<?php

namespace App\Http\Requests\master\feature;

use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Feature;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class FeatureListRequest extends FormRequest
{
  const MIN = 0;
  const MAX = 50;
  const MAX_DESCRIPTION = 100;

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
      'name' => 'string|min:0|max:50',
      'group_name' => 'string|min:0|max:50',
      'description' => 'string|min:0|max:100',
      'status' => 'in:' . implode(',', array_values(Feature::FEATURE_STATUS)),
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
      'name' => 'Feature name',
      'group_name' => 'Feature group name',
      'description' => 'Feature description',
      'status' => 'Feature status',
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
          'number' => self::MAX
        ]
      ),

      /**
       * Group
       */
      'group_name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => $this->attributes()['group_name']]
      ),
      'group_name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['group_name'],
          'number' => self::MIN
        ]
      ),
      'group_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => $this->attributes()['group_name'],
          'number' => self::MAX
        ]
      ),

      /**
       * Description
       */
      'description.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => $this->attributes()['description']]
      ),
      'description.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['description'],
          'number' => self::MIN
        ]
      ),
      'description.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => $this->attributes()['description'],
          'number' => self::MAX_DESCRIPTION
        ]
      ),

      /**
       * Status
       */
      'status.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => $this->attributes()['status'],
          'range' => implode(',', array_values(Feature::FEATURE_STATUS))
        ]
      ),

      /**
       * From date
       */
      'from_date.numeric' => Messages::getMessage(
        Messages::E0012,
        ['attributes' => $this->attributes()['from_date']]
      ),
      'from_date.date_format' => Messages::getMessage(
        Messages::E0013,
        [
          'attributes' => $this->attributes()['from_date'],
          'number' => self::MIN,
        ]
      ),

      /**
       * To date
       */
      'to_date.numeric' => Messages::getMessage(
        Messages::E0012,
        ['attributes' => $this->attributes()['to_date']]
      ),
      'to_date.date_format' => Messages::getMessage(
        Messages::E0013,
        [
          'attributes' => $this->attributes()['to_date'],
          'number' => self::MIN,
        ]
      ),
    ];
  }
}
