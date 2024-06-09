<?php

namespace Database\Factories\Master;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Category>
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
        $name = fake()->name();
        $description = fake()->text();

        return [
            'parent_id' => 0,
            'name' => (strlen($name) > 50) ? substr($name, 0, 50) : $name,
            'slug' => Str::slug((strlen($name) > 50) ? substr($name, 0, 50) : $name),
            'description' => (strlen($description) > 150) ? substr($description, 0, 150) : $description,
            'status' => fake()->random_int(1, 50),
            'is_display' => false,
            'rank_order' => fake()->random_int(1, 255),
        ];
    }
}
