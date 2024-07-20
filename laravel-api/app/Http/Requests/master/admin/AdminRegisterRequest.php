<?php

namespace App\Http\Requests\master\admin;

use App\Constants\CommonVal;
use App\Constants\Messages;
use App\Models\master\Admin;
use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
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
      'email' => 'email:rfc,dns|max:30|required|unique:App\Models\master\Admin,email',
      'user_name' => 'string|min:0|max:50|required|unique:App\Models\master\Admin,user_name',
      'password' => 'string|min:0|max:100|required',
      'first_name' => 'string|min:0|max:20|required',
      'last_name' => 'string|min:0|max:20|required',
      'address' => 'string|min:0|max:100',
      'phone_number' => 'string|min:0|max:20',
      'birth' => 'date_format:' . CommonVal::DATE_FORMAT,
      'gender' => 'integer',
      'status' => 'integer',
      'is_active' => 'boolean',
      'avatar' => 'string|min:0|max:30'
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
      'email.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['email'],
          'number' => Admin::$lengthAttr['thirty'],
        ]
      ),
      'email.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => Admin::attributes()['email']]
      ),
      'email.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => Admin::attributes()['email']]
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
          'number' => Admin::$lengthAttr['min']
        ]
      ),
      'user_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['user_name'],
          'number' => Admin::$lengthAttr['fifty']
        ]
      ),
      'user_name.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => Admin::attributes()['user_name']]
      ),
      'user_name.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => Admin::attributes()['user_name']]
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
          'number' => Admin::$lengthAttr['min']
        ]
      ),
      'password.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['password'],
          'number' => Admin::$lengthAttr['oneH']
        ]
      ),
      'password.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => Admin::attributes()['password']]
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
          'number' => Admin::$lengthAttr['min']
        ]
      ),
      'first_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['first_name'],
          'number' => Admin::$lengthAttr['twenty']
        ]
      ),
      'first_name.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => Admin::attributes()['first_name']]
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
          'number' => Admin::$lengthAttr['min']
        ]
      ),
      'last_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['last_name'],
          'number' => Admin::$lengthAttr['twenty']
        ]
      ),
      'last_name.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => Admin::attributes()['last_name']]
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
          'number' => Admin::$lengthAttr['min']
        ]
      ),
      'address.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['address'],
          'number' => Admin::$lengthAttr['oneH']
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
          'number' => Admin::$lengthAttr['min']
        ]
      ),
      'phone_number.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['phone_number'],
          'number' => Admin::$lengthAttr['twenty']
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
      'gender.integer' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => Admin::attributes()['gender']]
      ),

      /**
       * status
       */
      'status.integer' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => Admin::attributes()['status']]
      ),

      /**
       * is_active
       */
      'is_active.boolean' => Messages::getMessage(
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
          'number' => Admin::$lengthAttr['min']
        ]
      ),
      'avatar.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Admin::attributes()['avatar'],
          'number' => Admin::$lengthAttr['thirty']
        ]
      ),
    ];
  }
}
