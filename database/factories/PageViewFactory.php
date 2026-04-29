<?php

namespace Database\Factories;

use App\Models\PageView;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageView>
 */
class PageViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page' => fake()->randomElement(['/', '/projects', '/projects/m-kopa-website']),
            'ip_hash' => hash('sha256', fake()->ipv4()),
            'session_id' => fake()->uuid(),
            'user_agent' => fake()->userAgent(),
            'referrer' => fake()->optional()->url(),
            'country' => fake()->optional()->countryCode(),
            'viewed_at' => fake()->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
