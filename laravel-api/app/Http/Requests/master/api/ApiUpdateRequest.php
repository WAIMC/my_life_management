<?php

namespace App\Http\Requests\master\api;

use App\Models\master\Api;
use App\Constants\Messages;
use App\Models\master\Feature;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ApiUpdateRequest extends FormRequest
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
    $payload = $this->all();

    return [
      'type'       => 'in:' . implode(',', array_keys(Api::TYPE_OF_METHOD)),
      'name'       => ['string', 'min:0', 'max:50', Rule::unique(Api::class)->ignore($payload['id'])],
      'path'       => ['string', 'min:0', 'max:100', Rule::unique(Api::class)->ignore($payload['id'])],
      'is_active'  => 'bool',
      'feature_id' => 'integer|exists:t_feature,id',
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
       * Type
       */
      'type.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => Api::attributes()['type'],
          'range' => implode(',', array_keys(Api::TYPE_OF_METHOD))
        ]
      ),

      /**
       * Name
       */
      'name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Api::attributes()['name']]
      ),
      'name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Api::attributes()['name'],
          'number' => Api::LENGTH_ATTR[0]
        ]
      ),
      'name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Api::attributes()['name'],
          'number' => Api::LENGTH_ATTR[50]
        ]
      ),
      'name.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => Api::attributes()['name']]
      ),

      /**
       * Path
       */
      'path.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Api::attributes()['path']]
      ),
      'path.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Api::attributes()['path'],
          'number' => Api::LENGTH_ATTR[0]
        ]
      ),
      'path.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Api::attributes()['path'],
          'number' => Api::LENGTH_ATTR[100]
        ]
      ),
      'path.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => Api::attributes()['path']]
      ),

      /**
       * is_active
       */
      'is_active.bool' => Messages::getMessage(
        Messages::E0005,
        ['attributes' => Api::attributes()['is_active']]
      ),

      /**
       * Feature ID
       */
      'feature_id.integer' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => Api::attributes()['feature_id']]
      ),
      'feature_id.exists' => Messages::getMessage(
        Messages::E0017,
        [
          'attributes' => Api::attributes()['feature_id'],
          'tableName' => Feature::attributes()['table_name']
        ]
      ),
    ];
  }
}
