<?php

namespace Database\Factories;

use App\Models\ImpactArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ImpactArea>
 */
class ImpactAreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'tagline' => fake()->sentence(),
            'bullets' => [fake()->sentence(), fake()->sentence(), fake()->sentence()],
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
