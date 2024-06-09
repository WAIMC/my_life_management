<?php

namespace Database\Factories\History;

use App\Models\Master\SettingLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SettingLinkHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $settingLink = fake()->randomElement(SettingLink::all()->toArray());

        return [
            'setting_link_id' => $settingLink->id,
            'key' => $settingLink->key,
            'value' => $settingLink->value,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
