<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gateway;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Gateway::factory()->create([
            'name' => 'GatewayOne',
            'is_active' => true,
            'priority' => 1
        ]);

        Gateway::factory()->create([
            'name' => 'GatewayTwo',
            'is_active' => true,
            'priority' => 2
        ]);
    }
}
