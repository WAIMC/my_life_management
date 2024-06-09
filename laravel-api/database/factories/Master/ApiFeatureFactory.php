<?php

namespace Database\Factories\Master;

use App\Models\Master\Api;
use App\Models\Master\Feature;
use App\Models\Master\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\ApiFeature>
 */
class ApiFeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'api_id' => fake()->randomElement(Api::pluck('id')->toArray()),
            'feature_id' => fake()->randomElement(Feature::pluck('id')->toArray()),
            'role_id' => fake()->randomElement(Role::pluck('id')->toArray()),
        ];
    }
}
