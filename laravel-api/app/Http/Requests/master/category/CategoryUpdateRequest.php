<?php

namespace App\Http\Requests\master\category;

use App\Constants\Messages;
use App\Models\master\Category;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
    $params = $this->all();

    return [
      'parent_id'   => 'numeric|min:0',
      'name'        => ['string', 'min:0', 'max:50', Rule::unique(Category::class)->ignore($params['id'])],
      'slug'        => ['string', 'min:0', 'max:50', Rule::unique(Category::class)->ignore($params['id'])],
      'description' => 'string|min:0|max:150',
      'status'      => 'in:' . implode(',', array_values(Category::CATEGORY_STATUS)),
      'is_display'  => 'bool',
      'rank_order'  => 'numeric|min:0'
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
       * Parent Id
       */
      'parent_id.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => Category::attributes()['parent_id']]
      ),
      'parent_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Category::attributes()['parent_id'],
          'number' => Category::LENGTH_ATTR[0],
        ]
      ),

      /**
       * Name
       */
      'name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Category::attributes()['name']]
      ),
      'name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Category::attributes()['name'],
          'number' => Category::LENGTH_ATTR[0]
        ]
      ),
      'name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Category::attributes()['name'],
          'number' => Category::LENGTH_ATTR[50]
        ]
      ),
      'name.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => Category::attributes()['name']]
      ),

      /**
       * Slug
       */
      'slug.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Category::attributes()['slug']]
      ),
      'slug.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Category::attributes()['slug'],
          'number' => Category::LENGTH_ATTR[0]
        ]
      ),
      'slug.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Category::attributes()['slug'],
          'number' => Category::LENGTH_ATTR[50]
        ]
      ),
      'slug.unique' => Messages::getMessage(
        Messages::E0008,
        ['attributes' => Category::attributes()['slug']]
      ),

      /**
       * Description
       */
      'description.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Category::attributes()['description']]
      ),
      'description.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Category::attributes()['description'],
          'number' => Category::LENGTH_ATTR[0]
        ]
      ),
      'description.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Category::attributes()['description'],
          'number' => Category::LENGTH_ATTR[150]
        ]
      ),

      /**
       * Status
       */
      'status.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => Category::attributes()['status'],
          'range' => implode(',', array_values(Category::CATEGORY_STATUS))
        ]
      ),

      /**
       * Is display
       */
      'is_display.bool' => Messages::getMessage(
        Messages::E0005,
        ['attributes' => Category::attributes()['is_display']]
      ),

      /**
       * Rank order
       */
      'rank_order.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => Category::attributes()['rank_order']]
      ),
      'rank_order.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Category::attributes()['rank_order'],
          'number' => Category::LENGTH_ATTR[0]
        ]
      ),
    ];
  }
}
