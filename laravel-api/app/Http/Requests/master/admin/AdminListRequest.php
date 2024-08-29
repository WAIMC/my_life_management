<?php

namespace App\Http\Requests\master\admin;

use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Admin;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AdminListRequest extends FormRequest
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
      'email'        => 'email:rfc,dns|min:0|max:30',
      'user_name'    => 'string|min:0|max:50',
      'first_name'   => 'string|min:0|max:20',
      'last_name'    => 'string|min:0|max:20',
      'address'      => 'string|min:0|max:100',
      'phone_number' => 'string|min:0|max:20',
      'birth'        => 'date_format:' . CommonVal::DATE_FORMAT,
      'gender'       => 'in:' . implode(',', array_values(ADMIN::GENDER)),
      'status'       => 'in:' . implode(',', array_values(ADMIN::ADMIN_STATUS)),
      'is_active'    => 'in:true,false',
      'avatar'       => 'string|min:0|max:30',
      'from_date'    => 'date_format:' . CommonVal::DATE_FORMAT,
      'to_date'      => 'after:from_date|date_format:'  . CommonVal::DATE_FORMAT,
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
       * email
       */
      'email.email' => Messages::getMessage(
        Messages::E0006,
        ['attributes' => Admin::attributes()['email']]
      ),
      'email.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Admin::attributes()['email'],
          'number' => Admin::LENGTH_ATTR[0]
        ]
      ),
      'email.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['email'],
          'number' => Admin::LENGTH_ATTR[30],
        ]
      ),

      /**
       * user_name
       */
      'user_name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Admin::attributes()['user_name']]
      ),
      'user_name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Admin::attributes()['user_name'],
          'number' => Admin::LENGTH_ATTR[0]
        ]
      ),
      'user_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['user_name'],
          'number' => Admin::LENGTH_ATTR[50]
        ]
      ),

      /**
       * first_name
       */
      'first_name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Admin::attributes()['first_name']]
      ),
      'first_name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Admin::attributes()['first_name'],
          'number' => Admin::LENGTH_ATTR[0]
        ]
      ),
      'first_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['first_name'],
          'number' => Admin::LENGTH_ATTR[20]
        ]
      ),

      /**
       * last_name
       */
      'last_name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Admin::attributes()['last_name']]
      ),
      'last_name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Admin::attributes()['last_name'],
          'number' => Admin::LENGTH_ATTR[0]
        ]
      ),
      'last_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['last_name'],
          'number' => Admin::LENGTH_ATTR[20]
        ]
      ),

      /**
       * address
       */
      'address.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Admin::attributes()['address']]
      ),
      'address.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Admin::attributes()['address'],
          'number' => Admin::LENGTH_ATTR[0]
        ]
      ),
      'address.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['address'],
          'number' => Admin::LENGTH_ATTR[100]
        ]
      ),

      /**
       * phone_number
       */
      'phone_number.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Admin::attributes()['phone_number']]
      ),
      'phone_number.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Admin::attributes()['phone_number'],
          'number' => Admin::LENGTH_ATTR[0]
        ]
      ),
      'phone_number.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['phone_number'],
          'number' => Admin::LENGTH_ATTR[20]
        ]
      ),

      /**
       * birth
       */
      'birth.date_format' => Messages::getMessage(
        Messages::E0009,
        ['attributes' => Admin::attributes()['birth']]
      ),

      /**
       * gender
       */
      'gender.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => Admin::attributes()['gender'],
          'range' => implode(',', array_values(ADMIN::GENDER))
        ]
      ),

      /**
       * status
       */
      'status.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => Admin::attributes()['status'],
          'range' => implode(',', array_values(ADMIN::ADMIN_STATUS))
        ]
      ),

      /**
       * is_active
       */
      'is_active.in' => Messages::getMessage(
        Messages::E0005,
        ['attributes' => Admin::attributes()['is_active']]
      ),

      /**
       * avatar
       */
      'avatar.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Admin::attributes()['avatar']]
      ),
      'avatar.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Admin::attributes()['avatar'],
          'number' => Admin::LENGTH_ATTR[0]
        ]
      ),
      'avatar.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['avatar'],
          'number' => Admin::LENGTH_ATTR[30]
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
