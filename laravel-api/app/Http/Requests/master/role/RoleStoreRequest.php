<?php

namespace App\Http\Requests\master\role;

use App\Constants\Messages;
use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
  const MIN = 0;
  const MAX_NAME = 30;
  const MAX_PERMISSION = 50;
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
      'name' => 'required|string|min:0|max:30|unique:App\Models\master\role,name',
      'permission' => 'required|string|min:0|max:50',
      'is_active' => 'required|in:true,false',
    ];
  }

  /**
   * Get custom attributes for validator errors.
   *
   * @return array<string, string>
   */
  public function attributes(): array
  {
    return [
      'name' => 'Role name',
      'permission' => 'Role description',
      'is_active' => 'Active role',
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
        ['attributes' => $this->attributes()['name']]
      ),
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
          'number' => self::MAX_NAME
        ]
      ),
      'name.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => $this->attributes()['name']]
      ),

      /**
       * Permission
       */
      'permission.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['permission']]
      ),
      'permission.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => $this->attributes()['permission']]
      ),
      'permission.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['permission'],
          'number' => self::MIN
        ]
      ),
      'permission.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => $this->attributes()['permission'],
          'number' => self::MAX_PERMISSION
        ]
      ),

      /**
       * Is active
       */
      'is_active.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['is_active']]
      ),
      'is_active.in' => Messages::getMessage(
        Messages::E0005,
        ['attributes' => $this->attributes()['is_active']]
      ),
    ];
  }
}
