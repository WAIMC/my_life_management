<?php

namespace Database\Factories\Management;

use App\Models\Management\SkillDescriptionMgmt;
use App\Models\Management\SkillMgmt;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillDescriptionMgmtFactory extends Factory
{
  protected $model = SkillDescriptionMgmt::class;

  public function definition()
  {
    return [
      'parent_id' => 0,
      'title' => $this->faker->sentence(3),
      'summary' => $this->faker->sentence(10),
      'article' => $this->faker->paragraph(3),
      'status' => 1,
      'is_display' => true,
      'rank_order' => $this->faker->numberBetween(1, 100),
      'skill_mgmt_id' => SkillMgmt::factory(),
      'is_delete' => false,
    ];
  }
}
