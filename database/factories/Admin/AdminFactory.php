<?php

namespace Database\Factories\Admin;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\AdminRoles;
use App\Enums\Status;
use Illuminate\Support\Facades\Hash;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AdminFactory extends Factory
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
            'password' => Hash::make('password'), // password
            'role' => AdminRoles::ADMIN->value,
            'status' => $this->faker->randomElement([Status::ACTIVE->value,Status::INACTIVE->value])
        ];
    }
}
