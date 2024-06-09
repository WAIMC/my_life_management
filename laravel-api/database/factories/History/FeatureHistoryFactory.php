<?php

namespace Database\Factories\History;

use App\Models\Master\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\FeatureHistory>
 */
class FeatureHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $feature = fake()->randomElement(Feature::all()->toArray());

        return [
            'feature_id' => $feature->id,
            'name' => $feature->name,
            'group' => $feature->group,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
