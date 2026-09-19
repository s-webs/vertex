<?php

namespace Database\Factories;

use App\Models\PageImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageImage>
 */
class PageImageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page_key' => 'home',
            'slot_key' => fake()->unique()->slug(2),
            'path' => 'https://example.com/image.jpg',
            'alt' => fake()->sentence(3),
        ];
    }
}
