<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Product;

class TransactionProductSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = Transaction::all();
        $products = Product::all();

        foreach ($transactions as $transaction) {


            $assigned = $products->random(min(\rand(1,3), $products->count()));

            foreach ($assigned as $product) {

                $transaction->products()->attach($product->id, [
                    'quantity' => \rand(1,5)
                ]);

            }

        }
    }
}