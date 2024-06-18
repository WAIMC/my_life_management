<?php

namespace App\Http\Requests\master\category;

use App\Common;
use App\Messages;
use Illuminate\Foundation\Http\FormRequest;

class CategoryListRequest extends FormRequest
{
  const MIN = 0;
  const MAX = 50;

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
      'parent_id' => 'numeric|min:0',
      'name' => 'string|min:0|max:50',
      'slug' => 'string|min:0|max:50',
      'description' => 'string|min:0|max:50',
      'status' => 'numeric|min:0',
      'is_display' => 'in:true,false',
      'rank_order' => 'numeric|min:0',
      'from_date' => 'date|date_format:' . Common::DATE_FORMAT,
      'to_date' => 'date|after:from_date|date_format:'  . Common::DATE_FORMAT,
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
      'parent_id' => 'parent id',
      'name' => 'name',
      'slug' => 'slug',
      'description' => 'description',
      'status' => 'status',
      'is_display' => 'display',
      'rank_order' => 'rank order',
      'from_date' => 'from date',
      'to_date' => 'to date',
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
        ['attributes' => $this->attributes()['parent_id']]
      ),
      'parent_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['parent_id'],
          'number' => self::MIN,
        ]
      ),

      /**
       * Name
       */
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

      /**
       * Slug
       */
      'slug.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => $this->attributes()['slug']]
      ),
      'slug.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['slug'],
          'number' => self::MIN
        ]
      ),
      'slug.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => $this->attributes()['slug'],
          'number' => self::MAX
        ]
      ),

      /**
       * Description
       */
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
          'number' => self::MAX
        ]
      ),

      /**
       * Status
       */
      'status.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => $this->attributes()['status']]
      ),
      'status.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['status'],
          'number' => self::MIN
        ]
      ),

      /**
       * Is display
       */
      'is_display.in' => Messages::getMessage(
        Messages::E0005,
        ['attributes' => $this->attributes()['is_display']]
      ),

      /**
       * Rank order
       */
      'rank_order.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => $this->attributes()['rank_order']]
      ),
      'rank_order.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => $this->attributes()['rank_order'],
          'number' => self::MIN,
        ]
      ),

      /**
       * From date
       */
      'from_date.numeric' => Messages::getMessage(
        Messages::E0012,
        ['attributes' => $this->attributes()['from_date']]
      ),
      'from_date.date_format' => Messages::getMessage(
        Messages::E0013,
        [
          'attributes' => $this->attributes()['from_date'],
          'number' => self::MIN,
        ]
      ),

      /**
       * To date
       */
      'to_date.numeric' => Messages::getMessage(
        Messages::E0012,
        ['attributes' => $this->attributes()['to_date']]
      ),
      'to_date.date_format' => Messages::getMessage(
        Messages::E0013,
        [
          'attributes' => $this->attributes()['to_date'],
          'number' => self::MIN,
        ]
      ),
      'to_date.after' => Messages::getMessage(
        Messages::E0014,
        [
          'attributes' => $this->attributes()['from_date'],
          'attributes2' => $this->attributes()['to_date']
        ]
      ),
    ];
  }
}
