<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(),
            'value' => fake()->sentence(),
            'type' => 'text',
            'group' => fake()->randomElement(['hero', 'credibility', 'contact', 'seo', 'integrations']),
            'label' => fake()->words(3, true),
        ];
    }
}
