<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_date' => now(),
            'status' => fake()->randomElement(['pending', 'paid', 'shipped']),
            'total_amount' => 0,
        ];
    }
}
