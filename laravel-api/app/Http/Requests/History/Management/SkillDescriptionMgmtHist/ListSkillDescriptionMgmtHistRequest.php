<?php

namespace App\Http\Requests\History\Management\SkillDescriptionMgmtHist;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\CommonVal;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\History\Management\SkillDescriptionMgmtHist;
use App\Enums\StatusEnum;
use App\Models\Management\SkillDescriptionMgmt;

class ListSkillDescriptionMgmtHistRequest extends FormRequest
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
            'skill_description_mgmt_id' => ['nullable', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'parent_id' => ['nullable', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'title' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:100',],
            'summary' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:255',],
            'article' => ['nullable', 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR,],
            'status' => ['nullable', new Enum(StatusEnum::class),],
            'is_display' => ['nullable', new Enum(IsActive::class),],
            'rank_order' => ['nullable',],
            'skill_id' => ['nullable', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'action' => ['nullable',],
            'author_id' => ['nullable', 'integer', 'min:' . CommonVal::MIN_INTEGER, 'max:' . CommonVal::MAX_INTEGER,],
            'from_date' => [
                'nullable',
                'date_format:' . CommonVal::DATE_FORMAT,
                'after_or_equal:' . CommonVal::MIN_DATE,
                'before_or_equal:' . CommonVal::MAX_DATE,
            ],
            'to_date' => [
                'nullable',
                'date_format:' . CommonVal::DATE_FORMAT,
                'after_or_equal:' . CommonVal::MIN_DATE,
                'before_or_equal:' . CommonVal::MAX_DATE,
                'after:from_date'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'skill_description_mgmt_id' => __('messages.skill_description_mgmt_id'),
            'parent_id' => __('messages.parent_id'),
            'title' => __('messages.title'),
            'summary' => __('messages.summary'),
            'article' => __('messages.article'),
            'status' => __('messages.status'),
            'is_display' => __('messages.is_display'),
            'rank_order' => __('messages.rank_order'),
            'skill_id' => __('messages.skill_id'),
            'action' => __('messages.action'),
            'author_id' => __('messages.author_id'),
            'from_date' => __('messages.from_date'),
            'to_date' => __('messages.to_date'),
        ];
    }
}
