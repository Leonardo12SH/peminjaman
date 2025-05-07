<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name_212102' => fake()->name(),
            'email_212102' => fake()->unique()->safeEmail(),
            'telephone_212102' => fake()->phoneNumber(),
            'role_212102' => fake()->randomElement(['admin', 'user']),
            'email_verified_at' => now(),
            'password_212102' => bcrypt('password'), // bisa juga pakai yang kamu tulis manual
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at_212102' => null,
        ]);
    }
}
