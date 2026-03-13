<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::paginate(10);

        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0'
        ]);

        $product = Product::create([
            'name' => $request->name,
            'amount' => $request->amount
        ]);

        return response()->json([
            'message' => 'Produto criado com sucesso',
            'data' => $product
        ], 201);
    }


    public function show(Product $product)
    {
        return response()->json([
            'data' => $product
        ], 200);
    }


    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric|min:0'
        ]);

        $product->update($request->only(['name','amount']));

        return response()->json([
            'message' => 'Produto atualizado',
            'data' => $product
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Produto removido'
        ], 200);
    }
}