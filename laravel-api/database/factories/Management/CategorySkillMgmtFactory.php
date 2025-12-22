<?php

namespace Database\Factories\Management;

use App\Models\Management\CategoryMgmt;
use App\Models\Management\CategorySkillMgmt;
use App\Models\Management\SkillMgmt;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategorySkillMgmtFactory extends Factory
{
  protected $model = CategorySkillMgmt::class;

  public function definition()
  {
    return [
      'category_mgmt_id' => CategoryMgmt::factory(),
      'skill_mgmt_id' => SkillMgmt::factory(),
    ];
  }
}
