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
    'company_id'   => Str::uuid(),
    'role_id'      => Str::uuid(),
    'full_name'    => $this->faker->name(),
    'email'        => $this->faker->unique()->safeEmail(),
    'phone_number' => $this->faker->unique()->phoneNumber(),
    'password'     => Hash::make('password'),
    'is_verified'  => true,
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
