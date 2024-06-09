<?php

namespace Database\Factories\History;

use App\Models\Master\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History\BannerHistory>
 */
class BannerHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $banner = fake()->randomElement(Banner::all()->toArray());

        return [
            'banner_id' => $banner->id,
            'title' => $banner->title,
            'slug' => $banner->slug,
            'description' => $banner->description,
            'link' => $banner->link,
            'image' => $banner->image,
            'position' => $banner->position,
            'status' => $banner->status,
            'action' => fake()->random_int(1, 3),
            'author_id' => fake()->random_int(1, 10),
        ];
    }
}
