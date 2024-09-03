<?php

namespace App\Http\Requests\master\adminDepartment;

use App\Constants\Messages;
use App\Models\master\AdminDepartment;
use Illuminate\Foundation\Http\FormRequest;

class AdminDepartmentUpdateRequest extends FormRequest
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
      'insert'                 => 'array',
      'insert.*'               => 'array',
      'insert.*.admin_id'      => 'required|numeric|min:0',
      'insert.*.department_id' => 'required|numeric|min:0',
      'delete'                 => 'array',
      'delete.*'               => 'array',
      'delete.*.admin_id'      => 'required|numeric|min:0',
      'delete.*.department_id' => 'required|numeric|min:0',
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
       * Admin id of payload insert
       */
      'insert.*.admin_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => AdminDepartment::attributes()['admin_id']]
      ),
      'insert.*.admin_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => AdminDepartment::attributes()['admin_id']]
      ),
      'insert.*.admin_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => AdminDepartment::attributes()['admin_id'],
          'number' => AdminDepartment::LENGTH_ATTR[0]
        ]
      ),

      /**
       * Department id of payload insert
       */
      'insert.*.department_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => AdminDepartment::attributes()['department_id']]
      ),
      'insert.*.department_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => AdminDepartment::attributes()['department_id']]
      ),
      'insert.*.department_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => AdminDepartment::attributes()['department_id'],
          'number' => AdminDepartment::LENGTH_ATTR[0]
        ]
      ),

      /**
       * Admin id of payload delete
       */
      'delete.*.admin_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => AdminDepartment::attributes()['admin_id']]
      ),
      'delete.*.admin_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => AdminDepartment::attributes()['admin_id']]
      ),
      'delete.*.admin_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => AdminDepartment::attributes()['admin_id'],
          'number' => AdminDepartment::LENGTH_ATTR[0]
        ]
      ),

      /**
       * Department id of payload delete
       */
      'delete.*.department_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => AdminDepartment::attributes()['department_id']]
      ),
      'delete.*.department_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => AdminDepartment::attributes()['department_id']]
      ),
      'delete.*.department_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => AdminDepartment::attributes()['department_id'],
          'number' => AdminDepartment::LENGTH_ATTR[0]
        ]
      ),
    ];
  }
}
