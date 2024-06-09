<?php

namespace Database\Factories\History;

use App\Models\Master\Social;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\SocialHistory>
 */
class SocialHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $social = fake()->randomElement(Social::all()->toArray());

        return [
            'social_id' => $social->id,
            'name' => $social->name,
            'url' => $social->url,
            'icon' => $social->icon,
            'description' => $social->description,
            'status' => $social->status,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
