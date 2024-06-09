<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\SettingLink>
 */
class SettingLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $key = fake()->name();
        $value = fake()->name();

        return [
            'key' => (strlen($key) > 30) ? substr($key, 0, 30) : $key,
            'value' => (strlen($value) > 100) ? substr($value, 0, 100) : $value,
        ];
    }
}
