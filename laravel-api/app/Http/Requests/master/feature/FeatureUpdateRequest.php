<?php

namespace App\Http\Requests\master\feature;

use App\Constants\Messages;
use App\Models\master\Feature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeatureUpdateRequest extends FormRequest
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
      'name'        => ['string', 'min:0', 'max:50', Rule::unique(Feature::class)->ignore($payload['id'])],
      'group_name'  => 'string|min:0|max:50',
      'description' => 'string|min:0|max:100',
      'status'      => 'in:' . implode(',', array_values(Feature::FEATURE_STATUS)),
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
      'name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Feature::attributes()['name']]
      ),
      'name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Feature::attributes()['name'],
          'number' => Feature::LENGTH_ATTR[0]
        ]
      ),
      'name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Feature::attributes()['name'],
          'number' => Feature::LENGTH_ATTR[50]
        ]
      ),
      'name.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => Feature::attributes()['name']]
      ),

      /**
       * Group
       */
      'group_name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Feature::attributes()['group_name']]
      ),
      'group_name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Feature::attributes()['group_name'],
          'number' => Feature::LENGTH_ATTR[0]
        ]
      ),
      'group_name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Feature::attributes()['group_name'],
          'number' => Feature::LENGTH_ATTR[50]
        ]
      ),

      /**
       * Description
       */
      'description.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Feature::attributes()['description']]
      ),
      'description.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Feature::attributes()['description'],
          'number' => Feature::LENGTH_ATTR[0]
        ]
      ),
      'description.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Feature::attributes()['description'],
          'number' => Feature::LENGTH_ATTR[100]
        ]
      ),

      /**
       * Status
       */
      'status.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => Feature::attributes()['status'],
          'range' => implode(',', array_values(Feature::FEATURE_STATUS))
        ]
      ),
    ];
  }
}
