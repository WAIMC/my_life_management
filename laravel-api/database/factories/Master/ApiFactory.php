<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Api>
 */
class ApiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $path = fake()->url();

        return [
            'type' => random_int(1, 10),
            'name' => (strlen($name) > 50) ? substr($name, 0, 50) : $name,
            'path' => (strlen($path) > 50) ? substr($path, 0, 50) : $path,
            'is_valid' => false,
        ];
    }
}
