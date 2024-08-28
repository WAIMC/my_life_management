<?php

namespace App\Http\Requests\master\feature;

use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Feature;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class FeatureListRequest extends FormRequest
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
      'name'        => 'string|min:0|max:50',
      'group_name'  => 'string|min:0|max:50',
      'description' => 'string|min:0|max:100',
      'status'      => 'in:' . implode(',', array_values(Feature::FEATURE_STATUS)),
      'from_date'   => 'date_format:' . CommonVal::DATE_FORMAT,
      'to_date'     => 'after:from_date|date_format:'  . CommonVal::DATE_FORMAT,
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
       * Name
       */
      'name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Feature::attributes()['name']]
      ),
      'name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Feature::attributes()['name'],
          'number' => Feature::LENGTH_ATTR[0]
        ]
      ),
      'name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Feature::attributes()['name'],
          'number' => Feature::LENGTH_ATTR[50]
        ]
      ),

      /**
       * Group
       */
      'group_name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Feature::attributes()['group_name']]
      ),
      'group_name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Feature::attributes()['group_name'],
          'number' => Feature::LENGTH_ATTR[0]
        ]
      ),
      'group_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Feature::attributes()['group_name'],
          'number' => Feature::LENGTH_ATTR[50]
        ]
      ),

      /**
       * Description
       */
      'description.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Feature::attributes()['description']]
      ),
      'description.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Feature::attributes()['description'],
          'number' => Feature::LENGTH_ATTR[0]
        ]
      ),
      'description.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Feature::attributes()['description'],
          'number' => Feature::LENGTH_ATTR[100]
        ]
      ),

      /**
       * Status
       */
      'status.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => Feature::attributes()['status'],
          'range' => implode(',', array_values(Feature::FEATURE_STATUS))
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
