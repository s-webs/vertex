<?php

namespace Database\Factories;

use App\Models\Work;
use App\Models\WorkPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkPhoto>
 */
class WorkPhotoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'work_id' => Work::factory(),
            'path' => 'https://example.com/photo.jpg',
            'alt' => fake()->sentence(3),
            'caption' => fake()->words(2, true),
            'sort_order' => 1,
        ];
    }
}
