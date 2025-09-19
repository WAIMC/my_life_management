<?php

namespace App\Http\Requests\Master\DepartmentManagement;

use App\Constants\Messages;
use App\Models\Master\DepartmentManagementMst;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentManagementMstUpdateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return false;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'insert'                        => 'array',
      'insert.*'                      => 'array',
      'insert.*.department_id'        => 'required|numeric|min:0',
      'insert.*.policy_department_id' => 'required|numeric|min:0',
      'delete'                        => 'array',
      'delete.*'                      => 'array',
      'delete.*.department_id'        => 'required|numeric|min:0',
      'delete.*.policy_department_id' => 'required|numeric|min:0',
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
       * DepartmentMst id of payload insert
       */
      'insert.*.department_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => DepartmentManagementMst::attributes()['department_id']]
      ),
      'insert.*.department_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => DepartmentManagementMst::attributes()['department_id']]
      ),
      'insert.*.department_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => DepartmentManagementMst::attributes()['department_id'],
          'number' => DepartmentManagementMst::LENGTH_ATTR[0]
        ]
      ),

      /**
       * Policy DepartmentMst id of payload insert
       */
      'insert.*.policy_department_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => DepartmentManagementMst::attributes()['policy_department_id']]
      ),
      'insert.*.policy_department_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => DepartmentManagementMst::attributes()['policy_department_id']]
      ),
      'insert.*.policy_department_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => DepartmentManagementMst::attributes()['policy_department_id'],
          'number' => DepartmentManagementMst::LENGTH_ATTR[0]
        ]
      ),

      /**
       * DepartmentMst id of payload delete
       */
      'delete.*.department_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => DepartmentManagementMst::attributes()['department_id']]
      ),
      'delete.*.department_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => DepartmentManagementMst::attributes()['department_id']]
      ),
      'delete.*.department_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => DepartmentManagementMst::attributes()['department_id'],
          'number' => DepartmentManagementMst::LENGTH_ATTR[0]
        ]
      ),

      /**
       * Policy department id of payload delete
       */
      'delete.*.policy_department_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => DepartmentManagementMst::attributes()['policy_department_id']]
      ),
      'delete.*.policy_department_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => DepartmentManagementMst::attributes()['policy_department_id']]
      ),
      'delete.*.policy_department_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => DepartmentManagementMst::attributes()['policy_department_id'],
          'number' => DepartmentManagementMst::LENGTH_ATTR[0]
        ]
      ),
    ];
  }
}
