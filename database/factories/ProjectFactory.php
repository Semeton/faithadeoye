<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'category' => fake()->randomElement(['Website', 'SEO & Lead Generation', 'Brand & Campaign', 'Content Marketing']),
            'title' => fake()->sentence(4),
            'company' => fake()->company(),
            'country' => fake()->country(),
            'year' => (string) fake()->year(),
            'the_problem' => fake()->paragraph(),
            'what_i_did' => [fake()->sentence(), fake()->sentence(), fake()->sentence()],
            'skills_tags' => ['Research', 'Copywriting', 'SEO'],
            'cover_image' => null,
            'is_featured' => false,
            'published' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
