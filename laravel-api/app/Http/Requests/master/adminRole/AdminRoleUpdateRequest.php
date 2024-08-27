<?php

namespace App\Http\Requests\master\adminRole;

use App\Constants\Messages;
use App\Models\master\Admin;
use Illuminate\Foundation\Http\FormRequest;

class AdminRoleUpdateRequest extends FormRequest
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
      'insert'            => 'array',
      'insert.*'          => 'array',
      'insert.*.admin_id' => 'required|numeric|min:0',
      'insert.*.role_id'  => 'required|numeric|min:0',
      'delete'            => 'array',
      'delete.*'          => 'array',
      'delete.*.admin_id' => 'required|numeric|min:0',
      'delete.*.role_id'  => 'required|numeric|min:0',
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
      'insert'   => 'Payload insert',
      'insert.*' => 'Items payload insert',
      'delete'   => 'Payload delete',
      'delete.*' => 'Items payload delete',
      'admin_id' => 'Admin id',
      'role_id'  => 'Role id',
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
       * Insert
       */
      'insert.array' => Messages::getMessage(
        Messages::E0019,
        ['attributes' => $this->attributes()['insert']]
      ),

      /**
       * Items payload insert
       */
      'insert.*.array' => Messages::getMessage(
        Messages::E0019,
        ['attributes' => $this->attributes()['insert.*']]
      ),

      /**
       * Delete
       */
      'delete.array' => Messages::getMessage(
        Messages::E0019,
        ['attributes' => $this->attributes()['delete']]
      ),

      /**
       * Items payload delete
       */
      'delete.*.array' => Messages::getMessage(
        Messages::E0019,
        ['attributes' => $this->attributes()['delete.*']]
      ),

      /**
       * Admin ID of payload insert
       */
      'insert.*.admin_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['admin_id']]
      ),
      'insert.*.admin_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => $this->attributes()['admin_id']]
      ),
      'insert.*.admin_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['admin_id'],
          'number' => Admin::$lengthAttr['min']
        ]
      ),

      /**
       * Role ID of payload insert
       */
      'insert.*.role_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['role_id']]
      ),
      'insert.*.role_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => $this->attributes()['role_id']]
      ),
      'insert.*.role_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['role_id'],
          'number' => Admin::$lengthAttr['min']
        ]
      ),

      /**
       * Api ID of payload delete
       */
      'delete.*.admin_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['admin_id']]
      ),
      'delete.*.admin_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => $this->attributes()['admin_id']]
      ),
      'delete.*.admin_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['admin_id'],
          'number' => Admin::$lengthAttr['min']
        ]
      ),

      /**
       * Role ID of payload delete
       */
      'delete.*.role_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['role_id']]
      ),
      'delete.*.role_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => $this->attributes()['role_id']]
      ),
      'delete.*.role_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['role_id'],
          'number' => Admin::$lengthAttr['min']
        ]
      ),
    ];
  }
}
