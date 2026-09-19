<?php

namespace Database\Factories;

use App\Enums\SeoOwnerType;
use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SeoMeta>
 */
class SeoMetaFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_type' => SeoOwnerType::Page,
            'owner_key' => 'home',
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(12),
            'og_title' => null,
            'og_description' => null,
            'og_image' => null,
            'canonical' => null,
            'robots' => 'index,follow',
        ];
    }
}
