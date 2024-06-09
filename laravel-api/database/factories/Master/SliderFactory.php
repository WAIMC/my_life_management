<?php

namespace Database\Factories\Master;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Slider>
 */
class SliderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->title();
        $link = fake()->url();
        $image = fake()->image();

        return [
            'title' => (strlen($title) > 50) ? substr($title, 0, 50) : $title,
            'slug' => Str::slug((strlen($title) > 50) ? substr($title, 0, 50) : $title),
            'link' => (strlen($link) > 100) ? substr($link, 0, 100) : $link,
            'image' => (strlen($image) > 100) ? substr($image, 0, 100) : $image,
            'status' => fake()->random_int(1, 10),
        ];
    }
}
