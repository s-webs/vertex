<?php

namespace Database\Factories;

use App\Models\Catalog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Catalog>
 */
class CatalogFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'slug' => fake()->unique()->slug(),
            'brand' => fake()->company(),
            'year' => '2026',
            'title' => $title,
            'description' => fake()->sentence(12),
            'tags' => ['Пассажирские'],
            'cover_path' => 'https://example.com/cover.png',
            'pdf_path' => null,
            'page_count' => 12,
            'sort_order' => 1,
            'is_published' => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => false,
        ]);
    }
}
