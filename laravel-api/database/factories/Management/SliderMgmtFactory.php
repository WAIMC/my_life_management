<?php

namespace Database\Factories\Management;

use App\Models\Management\SliderMgmt;
use Illuminate\Database\Eloquent\Factories\Factory;

class SliderMgmtFactory extends Factory
{
  protected $model = SliderMgmt::class;

  public function definition()
  {
    return [
      'title' => $this->faker->sentence(3),
      'slug' => $this->faker->slug(),
      'link' => $this->faker->url(),
      'image' => $this->faker->imageUrl(640, 480),
      'status' => 1,
      'is_delete' => false,
    ];
  }
}
