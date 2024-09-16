<?php

namespace App\Http\Requests\master\skill;

use App\Constants\Messages;
use App\constants\CommonVal;
use App\Models\master\Skill;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SkillListRequest extends FormRequest
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
    return [
      'parent_id'   => 'numeric|min:0',
      'name'        => 'string|min:0|max:50',
      'slug'        => 'string|min:0|max:50',
      'status'      => 'in:' . implode(',', array_keys(Skill::SKILL_STATUS)),
      'is_display'  => 'in:true,false',
      'rank_order'  => 'numeric|min:0',
      'from_date'   => 'date_format:' . CommonVal::DATE_FORMAT,
      'to_date'     => 'after:from_date|date_format:'  . CommonVal::DATE_FORMAT,
    ];
  }

  /**
   * Configure the validator instance.
   *
   * @param  \Illuminate\Validation\Validator  $validator
   * @return void
   */
  public function withValidator(Validator $validator)
  {
    $validator->after(function ($validator) {
      if ($this->from_date && $this->to_date) {
        $fromDate = \DateTime::createFromFormat(CommonVal::DATE_FORMAT, $this->from_date);
        $toDate = \DateTime::createFromFormat(CommonVal::DATE_FORMAT, $this->to_date);

        if ($fromDate >= $toDate) {
          $validator->errors()->add(
            'to_date',
            Messages::getMessage(
              Messages::E0014,
              [
                'attributes' => $this->attributes()['from_date'],
                'attributes2' => $this->attributes()['to_date']
              ]
            )
          );
        }
      }
    });
  }

  /**
   * Get custom attributes for validator errors.
   *
   * @return array<string, string>
   */
  public function attributes(): array
  {
    return [
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
        ['attributes' => Skill::attributes()['parent_id']]
      ),
      'parent_id.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Skill::attributes()['parent_id'],
          'number' => Skill::LENGTH_ATTR[0],
        ]
      ),

      /**
       * Name
       */
      'name.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Skill::attributes()['name']]
      ),
      'name.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Skill::attributes()['name'],
          'number' => Skill::LENGTH_ATTR[0]
        ]
      ),
      'name.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Skill::attributes()['name'],
          'number' => Skill::LENGTH_ATTR[50]
        ]
      ),

      /**
       * Slug
       */
      'slug.string' => Messages::getMessage(
        Messages::E0002,
        ['attributes' => Skill::attributes()['slug']]
      ),
      'slug.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Skill::attributes()['slug'],
          'number' => Skill::LENGTH_ATTR[0]
        ]
      ),
      'slug.max' => Messages::getMessage(
        Messages::E0011,
        [
          'attributes' => Skill::attributes()['slug'],
          'number' => Skill::LENGTH_ATTR[50]
        ]
      ),

      /**
       * Status
       */
      'status.in' => Messages::getMessage(
        Messages::E0015,
        [
          'attributes' => Skill::attributes()['status'],
          'range' => implode(',', array_keys(Skill::SKILL_STATUS))
        ]
      ),

      /**
       * Is display
       */
      'is_display.in' => Messages::getMessage(
        Messages::E0005,
        ['attributes' => Skill::attributes()['is_display']]
      ),

      /**
       * Rank order
       */
      'rank_order.numeric' => Messages::getMessage(
        Messages::E0001,
        ['attributes' => Skill::attributes()['rank_order']]
      ),
      'rank_order.min' => Messages::getMessage(
        Messages::E0010,
        [
          'attributes' => Skill::attributes()['rank_order'],
          'number' => Skill::LENGTH_ATTR[0]
        ]
      ),

      /**
       * From date
       */
      'from_date.date_format' => Messages::getMessage(
        Messages::E0013,
        ['attributes' => $this->attributes()['from_date']]
      ),

      /**
       * To date
       */
      'to_date.date_format' => Messages::getMessage(
        Messages::E0013,
        ['attributes' => $this->attributes()['to_date']]
      ),
    ];
  }
}
