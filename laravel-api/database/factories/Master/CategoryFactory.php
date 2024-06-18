<?php

namespace Database\Factories\master;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => 0,
            'name' => fake()->name(50),
            'slug' => fake()->text(40),
            'description' => fake()->text(150),
            'status' => rand(0, 1),
            'is_display' => false,
            'rank_order' => rand(0, 1)
        ];
    }
}
