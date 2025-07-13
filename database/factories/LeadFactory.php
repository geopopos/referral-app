<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'company' => $this->faker->company(),
            'how_heard_about_us' => $this->faker->optional(0.7)->sentence(10),
            'status' => $this->faker->randomElement(['lead', 'appointment_scheduled', 'appointment_completed', 'offer_made', 'sale', 'lost']),
            'referral_code' => null, // Will be set by seeder if needed
        ];
    }

    /**
     * Indicate that the lead has a referrer.
     */
    public function withReferrer(User $referrer): static
    {
        return $this->state(fn (array $attributes) => [
            'referral_code' => $referrer->referral_code,
        ]);
    }

    /**
     * Indicate that the lead is new.
     */
    public function newStatus(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'lead',
        ]);
    }

    /**
     * Indicate that the lead has an appointment scheduled.
     */
    public function appointmentScheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'appointment_scheduled',
        ]);
    }

    /**
     * Indicate that the lead is a sale.
     */
    public function sale(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sale',
        ]);
    }

    /**
     * Indicate that the lead is lost.
     */
    public function lost(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'lost',
        ]);
    }
}
