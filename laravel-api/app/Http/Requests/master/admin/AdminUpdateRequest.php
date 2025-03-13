<?php

namespace App\Http\Requests\master\admin;

use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Admin;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateRequest extends FormRequest
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
      'id'           => ['numeric', 'required'],
      'password'     => ['string', 'min:0', 'max:100'],
      'first_name'   => ['string', 'min:0', 'max:20'],
      'last_name'    => ['string', 'min:0', 'max:20'],
      'address'      => ['string', 'min:0', 'max:100'],
      'phone_number' => ['string', 'min:0', 'max:20'],
      'birth'        => ['date_format:' . CommonVal::DATE_FORMAT],
      'gender'       => ['in:' . implode(',', array_values(Admin::GENDER))],
      'status'       => ['in:' . implode(',', array_values(Admin::ADMIN_STATUS))],
      'is_active'    => ['bool'],
      'avatar'       => ['string', 'min:0', 'max:30']
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
       * ID
       */
      'id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => Admin::attributes()['id']]
      ),
      'id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => Admin::attributes()['id']]
      ),

      /**
       * password
       */
      'password.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Admin::attributes()['password']]
      ),
      'password.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Admin::attributes()['password'],
          'number' => Admin::LENGTH_ATTR[0]
        ]
      ),
      'password.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['password'],
          'number' => Admin::LENGTH_ATTR[100]
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
          'range' => implode(',', array_values(Admin::GENDER))
        ]
      ),

      /**
       * status
       */
      'status.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => Admin::attributes()['status'],
          'range' => implode(',', array_values(Admin::ADMIN_STATUS))
        ]
      ),

      /**
       * is_active
       */
      'is_active.bool' => Messages::getMessage(
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
    ];
  }
}
