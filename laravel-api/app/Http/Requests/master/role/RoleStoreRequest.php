<?php

namespace App\Http\Requests\master\role;

use App\Constants\Messages;
use App\Models\master\Role;
use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
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
      'name'       => 'required|string|min:0|max:30|unique:App\Models\master\role,name',
      'permission' => 'string|min:0|max:50',
      'is_active'  => 'bool',
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
      'name.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => Role::attributes()['name']]
      ),
      'name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Role::attributes()['name']]
      ),
      'name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Role::attributes()['name'],
          'number' => Role::LENGTH_ATTR[0]
        ]
      ),
      'name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Role::attributes()['name'],
          'number' => Role::LENGTH_ATTR[30]
        ]
      ),
      'name.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => Role::attributes()['name']]
      ),

      /**
       * Permission
       */
      'permission.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Role::attributes()['permission']]
      ),
      'permission.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Role::attributes()['permission'],
          'number' => Role::LENGTH_ATTR[0]
        ]
      ),
      'permission.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Role::attributes()['permission'],
          'number' => Role::LENGTH_ATTR[50]
        ]
      ),

      /**
       * is_active
       */
      'is_active.bool' => Messages::getMessage(
        Messages::E0005,
        ['attributes' => Role::attributes()['is_active']]
      ),
    ];
  }
}
