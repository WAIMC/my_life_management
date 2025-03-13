<?php

namespace App\Http\Requests\master\policyDepartment;

use App\Constants\Messages;
use App\Models\master\PolicyDepartment;
use Illuminate\Foundation\Http\FormRequest;

class PolicyDepartmentStoreRequest extends FormRequest
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
      'table_name' => 'required|string|min:0|max:20',
      'row_id'     => 'required|numeric|min:0|',
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
       * Table name
       */
      'table_name.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => PolicyDepartment::attributes()['table_name']]
      ),
      'table_name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => PolicyDepartment::attributes()['table_name']]
      ),
      'table_name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => PolicyDepartment::attributes()['table_name'],
          'number' => PolicyDepartment::LENGTH_ATTR[0]
        ]
      ),
      'table_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => PolicyDepartment::attributes()['table_name'],
          'number' => PolicyDepartment::LENGTH_ATTR[20]
        ]
      ),

      /**
       * row id
       */
      'row_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => PolicyDepartment::attributes()['row_id']]
      ),
      'row_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => PolicyDepartment::attributes()['row_id']]
      ),
      'row_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => PolicyDepartment::attributes()['row_id'],
          'number' => PolicyDepartment::LENGTH_ATTR[0]
        ]
      ),
    ];
  }
}
