<?php

namespace App\Http\Requests\Master\Department;

use App\Constants\Messages;
use App\Models\Master\DepartmentMst;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentMstStoreRequest extends FormRequest
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
      'status' => 'in:' . implode(',', array_values(DepartmentMst::STATUS))
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
        ['attributes' => DepartmentMst::attributes()['code']]
      ),
      'code.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => DepartmentMst::attributes()['code']]
      ),
      'code.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => DepartmentMst::attributes()['code'],
          'number' => DepartmentMst::LENGTH_ATTR[0]
        ]
      ),
      'code.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => DepartmentMst::attributes()['code'],
          'number' => DepartmentMst::LENGTH_ATTR[50]
        ]
      ),

      /**
       * name
       */
      'name.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => DepartmentMst::attributes()['name']]
      ),
      'name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => DepartmentMst::attributes()['name']]
      ),
      'name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => DepartmentMst::attributes()['name'],
          'number' => DepartmentMst::LENGTH_ATTR[0]
        ]
      ),
      'name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => DepartmentMst::attributes()['name'],
          'number' => DepartmentMst::LENGTH_ATTR[50]
        ]
      ),

      /**
       * status
       */
      'status.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => DepartmentMst::attributes()['status'],
          'range' => implode(',', array_values(DepartmentMst::STATUS))
        ]
      ),
    ];
  }
}
