<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Social>
 */
class SocialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $url = fake()->url();
        $icon = fake()->name();
        $description = fake()->text();

        return [
            'name' => (strlen($name) > 30) ? substr($name, 0, 30) : $name,
            'url' => (strlen($url) > 100) ? substr($url, 0, 100) : $url,
            'icon' => (strlen($icon) > 30) ? substr($icon, 0, 30) : $icon,
            'description' => (strlen($description) > 100) ? substr($description, 0, 100) : $description,
            'status' => fake()->random_int(1, 10),
        ];
    }
}
