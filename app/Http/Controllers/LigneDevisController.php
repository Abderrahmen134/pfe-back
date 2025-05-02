<?php

namespace App\Http\Controllers;

use App\Models\LigneDevis;
use App\Models\Product;

use Illuminate\Http\Request;

class LigneDevisController extends Controller
{
    public function index()
    {
        return LigneDevis::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_devis' => 'required|integer|exists:devis,id',
            'id_product' => 'required|integer|exists:products,id',
            'quantite' => 'required|integer|min:1',
            //'remise' => 'nullable|numeric|min:0|max:100',
        ]);
    
        // Récupérer le produit pour connaître le prix
        $product = Product::findOrFail($validated['id_product']);
        $prixUnitaire = $product->price;
        $quantite = $validated['quantite'];
        $remise = $validated['remise'] ?? 0;
        $tva = 19;
    
        // Calcul du total HT après remise
        $total_ht = ($prixUnitaire * $quantite) * (1 - ($remise / 100));
        $total_ttc = $total_ht * (1 + ($tva / 100));
    
        $ligne = LigneDevis::create([
            'id_devis' => $validated['id_devis'],
            'id_product' => $validated['id_product'],
            'quantite' => $quantite,
            'remise' => $remise,
            'total_ht' => $total_ht,
            'tva' => $tva,
            'total_ttc' => $total_ttc,
        ]);
    
        return response()->json([
            'message' => 'Ligne de devis créée avec succès',
            'ligne_devis' => $ligne
        ], 201);
    }

    public function show($id)
    {
        return LigneDevis::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $ligne = LigneDevis::findOrFail($id);
        $ligne->update($request->all());

        return $ligne;
    }

    public function destroy($id)
    {
        return LigneDevis::destroy($id);
    }
}
