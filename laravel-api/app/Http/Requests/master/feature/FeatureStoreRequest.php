<?php

namespace App\Http\Requests\master\feature;

use App\Constants\Messages;
use App\Models\master\Feature;
use Illuminate\Foundation\Http\FormRequest;

class FeatureStoreRequest extends FormRequest
{
  const MIN = 0;
  const MAX = 50;
  const MAX_DESCRIPTION = 100;

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
      'name' => 'required|string|min:0|max:50|unique:App\Models\master\feature,name',
      'group_name' => 'required|string|min:0|max:50',
      'description' => 'required|string|min:0|max:100',
      'status' => 'required|in:' . implode(',', array_values(Feature::FEATURE_STATUS)),
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
      'name' => 'Feature name',
      'group_name' => 'Feature group name',
      'description' => 'Feature description',
      'status' => 'Feature status'
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
          'number' => self::MAX
        ]
      ),
      'name.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => $this->attributes()['name']]
      ),

      /**
       * Group
       */
      'group_name.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['group_name']]
      ),
      'group_name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => $this->attributes()['group_name']]
      ),
      'group_name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['group_name'],
          'number' => self::MIN
        ]
      ),
      'group_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => $this->attributes()['group_name'],
          'number' => self::MAX
        ]
      ),

      /**
       * Description
       */
      'description.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['description']]
      ),
      'description.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => $this->attributes()['description']]
      ),
      'description.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['description'],
          'number' => self::MIN
        ]
      ),
      'description.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => $this->attributes()['description'],
          'number' => self::MAX_DESCRIPTION
        ]
      ),

      /**
       * Status
       */
      'status.required' => Messages::getMessage(
        Messages::E0007,
        ['attributes' => $this->attributes()['status']]
      ),
      'status.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => $this->attributes()['status'],
          'range' => implode(',', array_values(Feature::FEATURE_STATUS))
        ]
      ),
    ];
  }
}
