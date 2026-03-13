<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GatewayFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'GatewayOne',
                'GatewayTwo'
            ]),
            'is_active' => true,
            'priority' => fake()->numberBetween(1, 2),
        ];
    }
}