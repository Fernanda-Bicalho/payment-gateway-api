<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Gateway;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'gateway_id' => Gateway::factory(),
            'external_id' => fake()->uuid(),
            'status' => fake()->randomElement(['paid','pending','failed']),
            'amount' => fake()->randomFloat(2, 10, 500),
            'card_last_numbers' => fake()->numerify('####'),
        ];
    }
}
