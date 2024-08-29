<?php

namespace App\Http\Requests\master\apiRole;

use App\Constants\Messages;
use App\Models\master\ApiRole;
use Illuminate\Foundation\Http\FormRequest;

class ApiRoleUpdateRequest extends FormRequest
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
      'insert'           => 'array',
      'insert.*'         => 'array',
      'insert.*.api_id'  => 'required|numeric|min:0',
      'insert.*.role_id' => 'required|numeric|min:0',
      'delete'           => 'array',
      'delete.*'         => 'array',
      'delete.*.api_id'  => 'required|numeric|min:0',
      'delete.*.role_id' => 'required|numeric|min:0',
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
       * Api ID of payload insert
       */
      'insert.*.api_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => ApiRole::attributes()['api_id']]
      ),
      'insert.*.api_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => ApiRole::attributes()['api_id']]
      ),
      'insert.*.api_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => ApiRole::attributes()['api_id'],
          'number' => ApiRole::LENGTH_ATTR[0]
        ]
      ),

      /**
       * Role ID of payload insert
       */
      'insert.*.role_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => ApiRole::attributes()['role_id']]
      ),
      'insert.*.role_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => ApiRole::attributes()['role_id']]
      ),
      'insert.*.role_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => ApiRole::attributes()['role_id'],
          'number' => ApiRole::LENGTH_ATTR[0]
        ]
      ),

      /**
       * Api ID of payload delete
       */
      'delete.*.api_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => ApiRole::attributes()['api_id']]
      ),
      'delete.*.api_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => ApiRole::attributes()['api_id']]
      ),
      'delete.*.api_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => ApiRole::attributes()['api_id'],
          'number' => ApiRole::LENGTH_ATTR[0]
        ]
      ),

      /**
       * Role ID of payload delete
       */
      'delete.*.role_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => ApiRole::attributes()['role_id']]
      ),
      'delete.*.role_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => ApiRole::attributes()['role_id']]
      ),
      'delete.*.role_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => ApiRole::attributes()['role_id'],
          'number' => ApiRole::LENGTH_ATTR[0]
        ]
      ),
    ];
  }
}
