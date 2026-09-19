<?php

namespace Database\Factories;

use App\Enums\InquiryStatus;
use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => '+7 (700) 000-00-00',
            'service' => 'Установка нового лифта',
            'message' => fake()->sentence(),
            'status' => InquiryStatus::New,
            'ip_address' => '127.0.0.1',
        ];
    }
}
