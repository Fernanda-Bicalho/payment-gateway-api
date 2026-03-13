<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\Gateway;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder {
    public function run(): void
    {

        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('123456'),
            'role' => 'admin'
        ]);


        User::factory()->count(5)->create();


        Client::factory()->count(10)->create();


        Product::factory()->count(20)->create();


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


        Transaction::factory()->count(30)->create()->each(function ($transaction) {

            $products = Product::inRandomOrder()
                ->take(fake()->numberBetween(1,3))
                ->get();

            foreach ($products as $product) {

                $transaction->products()->attach($product->id, [
                    'quantity' => fake()->numberBetween(1,5)
                ]);

            }

        });
    }
}