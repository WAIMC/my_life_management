<?php

namespace App\Http\Requests\master\api;

use App\Models\master\Api;
use App\Constants\Messages;
use App\Models\master\Feature;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ApiUpdateRequest extends FormRequest
{
  const MIN = 0;
  const MAX_name = 50;
  const MAX_PATH = 100;
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
      'type' => 'required|in:' . implode(',', array_keys(Api::TYPE_OF_METHOD)),
      'name' => ['required', 'string', 'min:0', 'max:50', Rule::unique(Api::class)->ignore($payload['id'])],
      'path' => ['required', 'string', 'min:0', 'max:100', Rule::unique(Api::class)->ignore($payload['id'])],
      'is_valid' => 'required|boolean',
      'feature_id' => 'required|integer|exists:t_feature,id',
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
      'type' => 'Type of api',
      'name' => 'Api name',
      'path' => 'Api path',
      'is_valid' => 'Api valid',
      'feature_id' => 'Feature ID',
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
      'type.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['type']]
      ),
      'type.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => $this->attributes()['type'],
          'range' => implode(',', array_keys(Api::TYPE_OF_METHOD))
        ]
      ),

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
          'number' => self::MAX_name
        ]
      ),
      'name.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => $this->attributes()['name']]
      ),

      /**
       * Path
       */
      'path.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['path']]
      ),
      'path.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => $this->attributes()['path']]
      ),
      'path.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['path'],
          'number' => self::MIN
        ]
      ),
      'path.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => $this->attributes()['path'],
          'number' => self::MAX_PATH
        ]
      ),
      'path.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => $this->attributes()['path']]
      ),

      /**
       * is_valid
       */
      'is_valid.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['is_valid']]
      ),
      'is_valid.boolean' => Messages::getMessage(
        Messages::E0005,
        ['attributes' => $this->attributes()['is_valid']]
      ),

      /**
       * Feature ID
       */
      'feature_id.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['feature_id']]
      ),
      'feature_id.integer' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => $this->attributes()['feature_id']]
      ),
      'feature_id.exists' => Messages::getMessage(
        Messages::E0017,
        [
          'attributes' => $this->attributes()['feature_id'],
          'tableName' => Feature::attributes()['table_name']
        ]
      ),
    ];
  }
}
