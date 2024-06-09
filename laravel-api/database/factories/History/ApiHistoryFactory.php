<?php

namespace Database\Factories\History;

use App\Models\Master\Api;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\ApiHistory>
 */
class ApiHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $api = fake()->randomElement(Api::all()->toArray());

        return [
            'api_id' => $api->id,
            'type' => $api->type,
            'name' => $api->name,
            'path' => $api->path,
            'is_valid' => $api->is_valid,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
