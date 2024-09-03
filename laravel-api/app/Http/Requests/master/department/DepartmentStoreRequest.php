<?php

namespace App\Http\Requests\master\department;

use App\Constants\Messages;
use App\Models\master\Department;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentStoreRequest extends FormRequest
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
      'code'   => 'required|string|min:0|max:50',
      'name'   => 'required|string|min:0|max:50',
      'status' => 'in:' . implode(',', array_values(Department::STATUS))
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
       * code
       */
      'code.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => Department::attributes()['code']]
      ),
      'code.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Department::attributes()['code']]
      ),
      'code.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Department::attributes()['code'],
          'number' => Department::LENGTH_ATTR[0]
        ]
      ),
      'code.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Department::attributes()['code'],
          'number' => Department::LENGTH_ATTR[50]
        ]
      ),

      /**
       * name
       */
      'name.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => Department::attributes()['name']]
      ),
      'name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Department::attributes()['name']]
      ),
      'name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Department::attributes()['name'],
          'number' => Department::LENGTH_ATTR[0]
        ]
      ),
      'name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Department::attributes()['name'],
          'number' => Department::LENGTH_ATTR[50]
        ]
      ),

      /**
       * status
       */
      'status.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => Department::attributes()['status'],
          'range' => implode(',', array_values(Department::STATUS))
        ]
      ),
    ];
  }
}
