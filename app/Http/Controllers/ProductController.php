<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Liste des produits
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // Formulaire de création (si web, sinon pas nécessaire pour API)
    public function create()
    {
        //
    }

    // Enregistrer un nouveau produit
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',

        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    // Afficher un produit
    public function show(Product $product)
    {
        return response()->json($product);
    }

    // Formulaire d'édition (si web, sinon pas nécessaire pour API)
    public function edit(Product $product)
    {
        //
    }

    // Mettre à jour un produit
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:0',
            'image' => 'required|string|max:255',
            'type_id' => 'sometimes|required|exists:types,id',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    // Supprimer un produit
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}