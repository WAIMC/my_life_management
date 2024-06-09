<?php

namespace Database\Factories\Master;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'parent_id' => 0,
            'name' => (strlen($name) > 50) ? substr($name, 0, 50) : $name,
            'slug' => Str::slug((strlen($name) > 50) ? substr($name, 0, 50) : $name),
            'status' => fake()->random_int(1, 255),
            'rank_order' => fake()->random_int(1, 255),
            'is_display' => false,
        ];
    }
}
