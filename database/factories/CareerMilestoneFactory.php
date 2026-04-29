<?php

namespace Database\Factories;

use App\Models\CareerMilestone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CareerMilestone>
 */
class CareerMilestoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'period' => fake()->year().' – '.fake()->year(),
            'role' => fake()->jobTitle(),
            'company' => fake()->optional()->company(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
