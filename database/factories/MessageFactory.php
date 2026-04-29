<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'subject' => fake()->sentence(5),
            'body' => fake()->paragraph(),
            'is_read' => false,
            'ip_hash' => hash('sha256', fake()->ipv4()),
            'received_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function read(): static
    {
        return $this->state(fn () => ['is_read' => true]);
    }
}
