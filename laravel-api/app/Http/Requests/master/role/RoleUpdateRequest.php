<?php

namespace App\Http\Requests\master\role;

use App\Constants\Messages;
use App\Models\master\Role;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
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
    $params = $this->all();

    return [
      'id'         => ['integer', 'required'],
      'name'       => ['string', 'min:0', 'max:30', Rule::unique(Role::class)->ignore($params['id'])],
      'permission' => ['string', 'min:0', 'max:50'],
      'is_active'  => ['bool'],
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
      'id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => Role::attributes()['id']]
      ),
      'id.integer' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => Role::attributes()['id']]
      ),

      /**
       * Name
       */
      'name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Role::attributes()['name']]
      ),
      'name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Role::attributes()['name'],
          'number' => Role::$lengthAttr[0]
        ]
      ),
      'name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Role::attributes()['name'],
          'number' => Role::$lengthAttr[30]
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
          'number' => Role::$lengthAttr[0]
        ]
      ),
      'permission.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Role::attributes()['permission'],
          'number' => Role::$lengthAttr[50]
        ]
      ),

      /**
       * Is active
       */
      'is_active.bool' => Messages::getMessage(
        Messages::E0005,
        ['attributes' => Role::attributes()['is_active']]
      ),
    ];
  }
}
