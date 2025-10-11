<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(), // ✅ mark as verified
            'password' => bcrypt('password'), // default fake password
            'birthdate' => $this->faker->date('Y-m-d', '2010-01-01'),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'role' => 'school',
            'schoolType' => $this->faker->randomElement(['elementary', 'highschool', 'university']),
            'jobType' => null,
            'avatar' => 'https://i.pravatar.cc/150?img=' . rand(1, 70),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
